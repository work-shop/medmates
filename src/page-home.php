<?php

$context = Timber::get_context();
$post = Timber::get_post();

$context["post"] = $post;
$context["posts"] = Timber::get_posts("post_type=post");

$templates = array("page-" . $post->post_name . ".twig", "page.twig");

Timber::render($templates, $context);
