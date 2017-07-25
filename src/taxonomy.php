<?php

$context = Timber::get_context();
$templates = array("taxonomy.twig", "archive.twig");

$term = get_queried_object();

array_unshift($templates, "taxonomy-$term->taxonomy.twig");

$context["wp_title"] = single_cat_title("", false);
$context["description"] = term_description($term->term_id, $term->taxonomy);

Timber::render($templates, $context);
