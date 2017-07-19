<?php

if ($_GET) {
  $s = get_query_var("s");

  if ($s != "") {
    wp_redirect(str_replace("%20", "+", esc_url(site_url("/search/$s"))));
  } else {
    wp_redirect(site_url($base_path));
  }
  exit();
}

$context = Timber::get_context();
$search_query = get_search_query();

$context["wp_title"] = ($search_query) ? "Search Results for “" . $search_query . "”" : "Search";

Timber::render("search.twig", $context);
