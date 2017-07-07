<?php

$context = Timber::get_context();
$post = Timber::get_post();

$context["post"] = $post;

$templates = array("single-$post->post_type.twig", "single.twig");

Timber::render($templates, $context);
