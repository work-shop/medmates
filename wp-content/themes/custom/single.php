<?php

$context = Timber::get_context();
$context["post"] = Timber::get_post();

$templates = array(
  "single-" . $post->ID . ".twig",
  "single-" . $post->post_type . ".twig",
  "single.twig"
);

Timber::render($templates, $context);
