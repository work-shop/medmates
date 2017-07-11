<?php

$context = Timber::get_context();
$page = Timber::get_post();

$context["page"] = $page;

if (is_front_page()) {
  $context["posts"] = Timber::get_posts("post_type=post");
}

$templates = array("page-$page->post_name.twig", "page.twig");

Timber::render($templates, $context);
