<?php

$context = Timber::get_context();
$templates = array("archive.twig");

$post_type_data = get_post_type_object(get_post_type());
$post_type = $post_type_data->name;
$post_type_archive_slug = $post_type_data->has_archive;

if ($post_type_archive_slug) {
  $post = Timber::get_post("pagename=$post_type_archive_slug");
  $context["post"] = $post;
}

array_unshift($templates, "archive-$post_type.twig");

Timber::render($templates, $context);
