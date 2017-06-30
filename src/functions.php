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
    add_action("init", array($this, "register_post_types"));
    add_action("init", array($this, "register_taxonomies"));
    add_action("wp_enqueue_scripts", array($this, "enqueue_scripts"));
    add_action("wp_enqueue_scripts", array($this, "enqueue_styles"));
    add_filter("timber_context", array($this, "add_to_context"));
    add_filter("get_twig", array($this, "add_to_twig"));
    parent::__construct();
  }

  function register_post_types() {
    $labels = array(
      "name" => __("Teams"),
      "singular_name" => __("Team")
    );

    $args = array(
      "labels" => $labels,
      "public" => true
    );

    register_post_type("team", $args);
  }

  function register_taxonomies() {
    // This is where you can register custom taxonomies
  }

  function enqueue_scripts() {
    $bundle_src = get_template_directory_uri() . "/bundle.js";
    $bundle_ver = filemtime(get_template_directory() . "/bundle.js");
    wp_enqueue_script("bundle", $bundle_src, array(), $bundle_ver, true);
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
