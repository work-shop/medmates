<?php

$context = Timber::get_context();

$context["wp_title"] = "Page Not Found";

Timber::render("404.twig", $context);
