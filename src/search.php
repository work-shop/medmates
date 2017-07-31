<?php

$context = Timber::get_context();

if (isset($_GET["s"])) {
  $s = $_GET["s"];
  $redirect_url = ($_GET["s"]) ? "/search/$s" : "/search";
  $redirect_url = esc_url($redirect_url);
  wp_redirect(esc_url($redirect_url));
  exit;
}

$s = get_query_var("s");
if (!$s) {
  $context["posts"] = [];
}

$search_query = get_search_query();
$context["wp_title"] = ($search_query) ? "Search Results for “" . $search_query . "”" : "Search";

Timber::render("search.twig", $context);
