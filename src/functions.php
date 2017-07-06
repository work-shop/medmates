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
    add_action("init", array($this, "remove_comment_support"));
    add_action("admin_menu", array($this, "remove_menu_items"));
    add_action("wp_before_admin_bar_render", array($this, "remove_admin_bar_items"));
    add_action("admin_menu", array($this, "change_post_menu_label"));
    add_action("init", array($this, "register_team_post_type"));
    add_action("init", array($this, "register_event_post_type"));
    add_action("init", array($this, "register_resource_post_type"));
    add_action("init", array($this, "register_event_category"));
    add_action("init", array($this, "register_resource_category"));
    add_action("wp_enqueue_scripts", array($this, "enqueue_scripts"));
    add_action("wp_enqueue_scripts", array($this, "enqueue_styles"));
    add_filter("timber_context", array($this, "add_to_context"));
    add_filter("get_twig", array($this, "add_to_twig"));
    parent::__construct();
  }

  // Remove comment support from posts and pages
  function remove_comment_support() {
    remove_post_type_support("post", "comments");
    remove_post_type_support("page", "comments");
  }

  // Remove comments from menu
  function remove_menu_items() {
    remove_menu_page("edit-comments.php");
  }

  // Remove comments from admin bar
  function remove_admin_bar_items() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu("comments");
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
      "has_archive" => true
    );

    register_post_type("team", $args);
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
      "taxonomies" => array("event_category"),
      "has_archive" => true
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
      "hierarchical" => true,
      "has_archive" => true
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
      "show_admin_column" => true
    );

    register_taxonomy("event-category", array("event"), $args);
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
      "show_admin_column" => true
    );

    register_taxonomy("resource-category", array("resource"), $args);
  }

  function enqueue_scripts() {
    wp_enqueue_script("jquery");

    $bundle_src = get_template_directory_uri() . "/bundle.js";
    $bundle_ver = filemtime(get_template_directory() . "/bundle.js");
    wp_enqueue_script("bundle", $bundle_src, array("jquery"), $bundle_ver, true);
  }

  function enqueue_styles() {
    $bundle_src = get_stylesheet_uri();
    $bundle_ver = filemtime(get_stylesheet_directory() . "/style.css");
    wp_enqueue_style("bundle", $bundle_src, array(), $bundle_ver);
  }

  function add_to_context($context) {
    $context["menu"] = new TimberMenu();
    return $context;
  }

  function add_to_twig($twig) {
    $twig->addExtension(new Twig_Extension_StringLoader());
    return $twig;
  }
}

new MyTimberSite();
