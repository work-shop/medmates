<?php

$context = Timber::get_context();

$context["page"] = Timber::get_post();
$context["posts"] = Timber::get_posts("post_type=event&numberposts=-1&orderby=title&order=ASC");

Timber::render("page-active-teams.twig", $context);
