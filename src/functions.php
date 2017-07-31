<?php

require_once(get_template_directory() . "/routes.php");

if (!class_exists("Timber")) {
  add_action("admin_notices", function () {
    echo "<div class='error'><p>Timber is not activated.
      Make sure you activate the plugin in <a href='" .
      esc_url(admin_url("plugins.php#timber")) . "'>" .
      esc_url(admin_url("plugins.php")) . "</a>.</p></div>";
  });
  return;
}

Timber::$dirname = "templates";

class MyTimberSite extends TimberSite {
  function __construct() {
    add_theme_support("post-formats");
    add_theme_support("post-thumbnails");
    add_theme_support("menus");
    add_action("admin_head", array($this, "my_profile_subject_start"));
    add_action("admin_footer", array($this, "my_profile_subject_end"));
    add_action("after_setup_theme", array($this, "remove_admin_bar_for_members"));
    add_action("admin_bar_menu", array($this, "remove_wp_logo_from_admin_bar"), 11);
    add_action("wp_dashboard_setup", array($this, "remove_dashboard_widgets"));
    add_action("init", array($this, "remove_comment_support"));
    add_action("admin_menu", array($this, "remove_menu_items"));
    add_action("wp_before_admin_bar_render", array($this, "remove_admin_bar_items"));
    add_action("init", array($this, "add_professional_role"));
    add_action("init", array($this, "add_company_role"));
    add_action("admin_menu", array($this, "change_post_menu_label"));
    add_action("init", array($this, "register_team_post_type"));
    add_action("init", array($this, "register_event_post_type"));
    add_action("init", array($this, "register_resource_post_type"));
    add_action("init", array($this, "register_event_category"));
    add_action("init", array($this, "register_resource_category"));
    add_action("wp_enqueue_scripts", array($this, "enqueue_scripts"));
    add_action("wp_enqueue_scripts", array($this, "enqueue_styles"));
    add_action("login_enqueue_scripts", array($this, "enqueue_login_styles"));
    add_action("user_register", array($this, "my_registration_save"));
    add_filter("acf/load_field", array($this, "my_acf_hide_field_on_profile_page"));
    add_filter("acf/load_field", array($this, "my_acf_load_user_role_field"));
    add_filter("login_headerurl", array($this, "my_login_header_url"));
    add_filter("login_headertitle", array($this, "my_login_header_title"));
    add_filter("timber_context", array($this, "add_to_context"));
    add_filter("get_twig", array($this, "add_to_twig"));
    remove_action("admin_color_scheme_picker", "admin_color_scheme_picker");
    remove_filter("template_redirect", "redirect_canonical");
    parent::__construct();
  }

  // Remove the leftover personal options from the profile page for members
  function my_remove_personal_options($subject) {
    if (current_user_can("professional") || current_user_can("company")) {
      $subject = preg_replace("#<h2>Personal Options</h2>.+?/table>#s", "", $subject, 1);
    }

    return $subject;
  }

  function my_profile_subject_start() {
    ob_start(array($this, "my_remove_personal_options"));
  }

  function my_profile_subject_end() {
    ob_end_flush();
  }

  // Remove the admin bar for members
  function remove_admin_bar_for_members() {
    if (current_user_can("professional") || current_user_can("company")) {
      show_admin_bar(false);
    }
  }

  // Remove WordPress logo from the admin bar
  function remove_wp_logo_from_admin_bar($wp_admin_bar) {
    $wp_admin_bar->remove_node("wp-logo");
  }

  // Remove Dashboard widgets
  function remove_dashboard_widgets () {
    remove_meta_box("dashboard_primary", "dashboard", "side");   // WordPress.com blog
    remove_meta_box("dashboard_secondary", "dashboard", "side"); // Other WordPress news
  }

  // Remove comment support from posts and pages
  function remove_comment_support() {
    remove_post_type_support("post", "comments");
    remove_post_type_support("page", "comments");
  }

  // Remove comments link from menu
  function remove_menu_items() {
    remove_menu_page("edit-comments.php");
  }

  // Remove comments link from admin bar
  function remove_admin_bar_items() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu("comments");
  }

  function add_professional_role() {
    $caps = array(
      "read" => true
    );

    add_role("professional", __("Professional"), $caps);
  }

  function add_company_role() {
    $caps = array(
      "read" => true
    );

    add_role("company", __("Company"), $caps);
  }

  // Change menu label for posts
  function change_post_menu_label() {
    global $menu;
    $menu[5][0] = "News";
    echo "";
  }

  function register_team_post_type() {
    $labels = array(
      "name" => __("Active Teams"),
      "singular_name" => __("Active Team"),
      "add_new_item" => __("Add New Active Team"),
      "edit_item" => __("Edit Active Team"),
      "new_item" => __("New Active Team"),
      "view_item" => __("View Active Team"),
      "view_items" => __("View Active Teams"),
      "search_items" => __("Search Active Teams"),
      "not_found" => __("No active teams found"),
      "not_found_in_trash" => __("No active teams found in Trash"),
      "all_items" => __("All Active Teams"),
      "archives" => __("Active Team Archives"),
      "attributes" => __("Active Team Attributes"),
      "insert_into_item" => __("Insert into active team"),
      "uploaded_to_this_item" => __("Uploaded to this active team")
    );

    $args = array(
      "labels" => $labels,
      "public" => true,
      "supports" => array("title", "editor", "excerpt", "thumbnail"),
      "rewrite" => array(
        "slug" => "active-team",
        "with_front" => false
      ),
      "show_in_rest" => true
    );

    register_post_type("active_team", $args);
  }

  function register_event_post_type() {
    $labels = array(
      "name" => __("Events"),
      "singular_name" => __("Event"),
      "add_new_item" => __("Add New Event"),
      "edit_item" => __("Edit Event"),
      "new_item" => __("New Event"),
      "view_item" => __("View Event"),
      "view_items" => __("View Events"),
      "search_items" => __("Search Events"),
      "not_found" => __("No events found"),
      "not_found_in_trash" => __("No events found in Trash"),
      "all_items" => __("All Events"),
      "archives" => __("Event Archives"),
      "attributes" => __("Event Attributes"),
      "insert_into_item" => __("Insert into event"),
      "uploaded_to_this_item" => __("Uploaded to this event")
    );

    $args = array(
      "labels" => $labels,
      "public" => true,
      "supports" => array("title", "editor", "excerpt", "thumbnail"),
      "has_archive" => "events",
      "taxonomies" => array("event_category"),
      "rewrite" => array(
        "slug" => "event",
        "with_front" => false
      ),
      "show_in_rest" => true
    );

    register_post_type("event", $args);
  }

  function register_resource_post_type() {
    $labels = array(
      "name" => __("Resources"),
      "singular_name" => __("Resource"),
      "add_new_item" => __("Add New Resource"),
      "edit_item" => __("Edit Resource"),
      "new_item" => __("New Resource"),
      "view_item" => __("View Resource"),
      "view_items" => __("View Resources"),
      "search_items" => __("Search Resources"),
      "not_found" => __("No resources found"),
      "not_found_in_trash" => __("No resources found in Trash"),
      "all_items" => __("All Resources"),
      "archives" => __("Resource Archives"),
      "attributes" => __("Resource Attributes"),
      "insert_into_item" => __("Insert into resource"),
      "uploaded_to_this_item" => __("Uploaded to this resource")
    );

    $args = array(
      "labels" => $labels,
      "public" => true,
      "supports" => array("title", "editor", "excerpt", "thumbnail", "page-attributes"),
      "has_archive" => "resources",
      "taxonomies" => array("resource_category"),
      "rewrite" => array(
        "slug" => "resource",
        "with_front" => false
      ),
      "show_in_rest" => true
    );

    register_post_type("resource", $args);
  }

  function register_event_category() {
    $labels = array(
      "name" => __("Event Categories"),
      "singular_name" => __("Event Category"),
      "search_items" => __("Search Event Categories"),
      "all_items" => __("All Event Categories"),
      "parent_item" => __("Parent Event Category"),
      "edit_item" => __("Edit Event Category"),
      "view_item" => __("View Event Category"),
      "update_item" => __("Update Event Category"),
      "add_new_item" => __("Add New Event Category"),
      "new_item_name" => __("New Event Category Name"),
      "not_found" => __("No event categories found"),
      "no_terms" => __("No event categories")
    );

    $args = array(
      "labels" => $labels,
      "public" => true,
      "hierarchical" => true,
      "show_admin_column" => true,
      "rewrite" => array(
        "slug" => "event-category",
        "with_front" => false
      ),
      "show_in_rest" => true
    );

    register_taxonomy("event_category", "event", $args);
  }

  function register_resource_category() {
    $labels = array(
      "name" => __("Resource Categories"),
      "singular_name" => __("Resource Category"),
      "search_items" => __("Search Resource Categories"),
      "all_items" => __("All Resource Categories"),
      "parent_item" => __("Parent Resource Category"),
      "edit_item" => __("Edit Resource Category"),
      "view_item" => __("View Resource Category"),
      "update_item" => __("Update Resource Category"),
      "add_new_item" => __("Add New Resource Category"),
      "new_item_name" => __("New Resource Category Name"),
      "not_found" => __("No resource categories found"),
      "no_terms" => __("No resource categories")
    );

    $args = array(
      "labels" => $labels,
      "public" => true,
      "hierarchical" => true,
      "show_admin_column" => true,
      "rewrite" => array(
        "slug" => "resource-category",
        "with_front" => false
      ),
      "show_in_rest" => true
    );

    register_taxonomy("resource_category", "resource", $args);
  }

  function my_registration_save($user_id) {
    $role = $_POST["acf"]["field_5968fd11a35f8"];

    // Format display name
    if ($role === "company") {
      $company_name = $_POST["acf"]["field_5968fd95463c1"];
      $first_name = $company_name;
      $display_name = $company_name;
    } else {
      $first_name = $_POST["acf"]["field_5968fabdd18fd"];
      $last_name = $_POST["acf"]["field_5968facbd18fe"];
      $display_name = $first_name . " " . $last_name;
    }

    $website = $_POST["acf"]["field_5968fb2b409b6"];
    $biography = $_POST["acf"]["field_596910b2fe57f"];

    $userdata = array(
      "ID" => $user_id,
      "role" => $role,
      "first_name" => $first_name,
      "last_name" => $last_name,
      "nickname" => $display_name,
      "display_name" => $display_name,
      "user_url" => $website,
      "description" => $biography
    );

    wp_update_user($userdata);
  }

  // Hide some ACF fields from the user profile page
  function my_acf_hide_field_on_profile_page($field) {
    global $pagenow;

    if (is_admin() && ($pagenow === "user-edit.php") || ($pagenow === "profile.php")) {
      $fields_to_hide = array(
        "field_5968fd11a35f8", // Member role
        "field_5968fabdd18fd", // First name
        "field_5968facbd18fe", // Last name
        "field_5968fd95463c1", // Company name
        "field_5968fb2b409b6", // Website URL
        "field_596910b2fe57f"  // Biography
      );

      if (in_array($field["key"], $fields_to_hide)) {
        $field["readonly"] = true;
        $field["conditional_logic"] = true;
      }
    }

    return $field;
  }

  // Set user role field for ACF fields in the profile page
  function my_acf_load_user_role_field($field) {
    global $pagenow;

    if (is_user_logged_in() && ($pagenow === "user-edit.php" || "profile.php")) {
      // Get user ID
      if (IS_PROFILE_PAGE)  {
        $user_id = get_current_user_id();
      } elseif (!empty($_GET["user_id"])) {
        $user_id = $_GET["user_id"];
      }

      $user = get_userdata($user_id);

      if ($field["key"] === "field_5968fd11a35f8") { // Member role
        $field["value"] = $user->roles[0];
      }
    }

    return $field;
  }

  // Set the URL of the login logo to the site URL
  function my_login_header_url() {
    return get_bloginfo("url");
  }

  // Set the title text on the login logo to the site name
  function my_login_header_title() {
    return get_option("blogname");
  }

  function enqueue_scripts() {
    wp_enqueue_script("jquery");

    $bundle_src = get_template_directory_uri() . "/scripts/bundle.js";
    $bundle_ver = filemtime(get_template_directory() . "/scripts/bundle.js");
    wp_enqueue_script("bundle", $bundle_src, array("jquery"), $bundle_ver, true);
  }

  function enqueue_styles() {
    $base_src = get_template_directory_uri() . "/styles/base.css";
    $base_ver = filemtime(get_template_directory() . "/styles/base.css");
    wp_enqueue_style("base", $base_src, array(), $base_ver);

    $main_src = get_template_directory_uri() . "/styles/main.css";
    $main_ver = filemtime(get_template_directory() . "/styles/main.css");
    wp_enqueue_style("main", $main_src, array("base"), $main_ver);
  }

  function enqueue_login_styles() {
    $base_src = get_template_directory_uri() . "/styles/base.css";
    $base_ver = filemtime(get_template_directory() . "/styles/base.css");
    wp_enqueue_style("base", $base_src, array(), $base_ver);

    $login_src = get_template_directory_uri() . "/styles/login.css";
    $login_ver = filemtime(get_template_directory() . "/styles/login.css");
    wp_enqueue_style("wp-login", $login_src, array("base"), $login_ver);
  }

  function add_to_context($context) {
    $context["menu"] = new TimberMenu();
    $context["search_query"] = get_search_query();
    $context["search_link"] = user_trailingslashit(get_site_url() . "/search");
    $context["join_link"] = wp_registration_url();
    $context["directory_link"] = user_trailingslashit(get_site_url() . "/members");
    $context["resources_link"] = user_trailingslashit(get_site_url() . "/resources");
    $context["login_link"] = wp_login_url(get_site_url());
    $context["logout_link"] = wp_logout_url(get_site_url());
    $context["profile_link"] = user_trailingslashit(get_site_url() . "/member/" . get_current_user_id());
    $context["edit_profile_link"] = get_edit_user_link();
    return $context;
  }

  function add_to_twig($twig) {
    $twig->addExtension(new Twig_Extension_StringLoader());
    return $twig;
  }
}

new MyTimberSite();
