<?php

$context = Timber::get_context();

echo "WHAT";

$templates = array("archive.twig");

if (is_front_page()) {
	array_unshift($templates, "home.twig");
}

Timber::render($templates, $context);
