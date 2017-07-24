<?php

$context = Timber::get_context();
$templates = array("taxonomy.twig", "archive.twig");

$taxonomy = get_query_var("taxonomy");
array_unshift($templates, "taxonomy-$taxonomy.twig");

$context["wp_title"] = single_cat_title("", false);

Timber::render($templates, $context);
