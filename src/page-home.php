<?php

$context = Timber::get_context();

$context["page"] = Timber::get_post();
$context["posts"] = Timber::get_posts("post_type=post");

Timber::render("page-home.twig", $context);
