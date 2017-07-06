<?php

$context = Timber::get_context();
$post = Timber::get_post();

$context["post"] = $post;

$templates = array("page-" . $post->post_name . ".twig", "page.twig");

Timber::render($templates, $context);
