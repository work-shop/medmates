<?php

$context = Timber::get_context();
$search_query = get_search_query();


$context["wp_title"] = ($search_query) ? "Search Results for “" . $search_query . "”" : "Search";

Timber::render("search.twig", $context);
