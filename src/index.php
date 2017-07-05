<?php

$context = Timber::get_context();

$posts = Timber::get_posts();
$context["posts"] = $posts;

$templates = array("index.twig");

if (is_home() && get_post_type() == "post") {
  array_unshift($templates, "home.twig");
} else {
  // Set wp_title to the plural post type label of the first post in $posts
  $post_type = $posts[0]->post_type;
  $post_type_object = get_post_type_object($post_type);
  $post_type_label = $post_type_object->labels->name;
  $context["wp_title"] = $post_type_label;
}

Timber::render($templates, $context);
