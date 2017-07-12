<?php

global $params;

$context = Timber::get_context();
$post = Timber::get_post();

$context["post"] = $post;

if (isset($params["posts"])) {
  $context["posts"] = $params["posts"];
} else {
  unset($context["posts"]);
}

$templates = array("page-$post->post_name.twig", "page.twig");

Timber::render($templates, $context);
