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
    add_action("init", array($this, "change_post_object_labels"));
    add_action("init", array($this, "register_post_types"));
    add_action("init", array($this, "register_taxonomies"));
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

  // Change object labels for posts
  function change_post_object_labels() {
    global $wp_post_types;
    $labels = &$wp_post_types["post"]->labels;
    $labels->name = "Articles";
    $labels->singular_name = "Article";
    $labels->add_new_item = "Add New Article";
    $labels->edit_item = "Edit Article";
    $labels->new_item = "New Article";
    $labels->view_item = "View Article";
    $labels->view_items = "View Articles";
    $labels->search_items = "Search Articles";
    $labels->not_found = "No articles found";
    $labels->not_found_in_trash = "No articles found in Trash";
    $labels->all_items = "All Articles";
    $labels->archives = "Article Archives";
    $labels->attributes = "Article Attributes";
    $labels->insert_into_item = "Insert into article";
    $labels->uploaded_to_this_item = "Uploaded to this article";
  }

  function register_post_types() {
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
      "rewrite" => array(
        "slug" => "active-teams",
        "with_front" => false
      )
    );

    register_post_type("active-team", $args);
  }

  function register_taxonomies() {
    // This is where you can register custom taxonomies
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
    $context["site"] = $this;
    $context["menu"] = new TimberMenu();
    $context["widgets"] = Timber::get_widgets("widgets");
    return $context;
  }

  function add_to_twig($twig) {
    $twig->addExtension(new Twig_Extension_StringLoader());
    return $twig;
  }
}

new MyTimberSite();
