<?php

$context = Timber::get_context();
$context["post"] = Timber::get_post();

$templates = array(
  "page-" . $post->post_name . ".twig",
  "page.twig"
);

Timber::render($templates, $context);
