<?php

global $params;

$context = Timber::get_context();
$search_query = get_search_query();
$member_role = $params["member_role"];
$per_page = $params["per_page"];
$paged = 1;

if (isset($params["page_number"])) {
  $paged = $params["page_number"];
}

set_query_var("paged", $paged);

// Get filters
$search_query = trim(preg_replace("/\s+/", " ", $search_query)); // Clean up whitespace
$search_query = str_replace("&quot;", "\"", $search_query); // Replace &quot; with "
$search_queries = str_getcsv($search_query, " ");
$filters = [];
$queries = [];

foreach ($search_queries as $query) {
  if (preg_match("/filter=(.*)/i", $query, $matches)) {
    $filters[] = $matches[1];
  } else {
    $queries[] = $query;
  }
}

if (!empty($search_queries)) {
  if (!empty($filters)) {
    $user_meta_query = array("relation" => "AND");
  } else {
    $user_meta_query = array("relation" => "OR");
  }

  if (!empty($filters)) {
    $user_meta_query_filters = array("relation" => "AND");

    foreach ($filters as $value) {
      $user_meta_query_filters[] = array(
        "key" => "industry_categories",
        "value" => $value,
        "compare" => "LIKE"
      );
    }

    $user_meta_query[] = $user_meta_query_filters;
  }

  $user_meta_query_extras = array("relation" => "OR");

  $meta_query_keys = array(
    "first_name",
    "last_name",
    "description",
    "job_title",
    "job_functions",
    "company_affiliation",
    "development_stage",
  );

  foreach ($meta_query_keys as $key) {
    foreach ($queries as $value) {
      $user_meta_query_extras[] = array(
        "key" => $key,
        "value" => $value,
        "compare" => "LIKE"
      );
    }
  }

  $user_meta_query[] = $user_meta_query_extras;
}

$user_query = new WP_User_Query(array(
  "offset" => $paged ? ($paged - 1) * $per_page : 0,
  "number" => $per_page,
  "role" => $member_role,
  "meta_key" => "last_name",
  "orderby" => "meta_value",
  "order" => "ASC",
  "meta_query" => $user_meta_query
));

$members = $user_query->get_results();
$total_members = $user_query->get_total();

// Add a permalink to each member object
for ($i = 0; $i < count($members); $i++) {
  $member = new Timber\User($members[$i]->ID); // Fetch member object so we have avatar data
  $member->link = user_trailingslashit(get_site_url() . "/member/$member->ID");
  $members[$i] = $member;
}

// Get pagination
if ($total_members > $per_page) {
  $big = 999999999;
  $base = str_replace($big, "%#%", esc_url(get_pagenum_link($big)));
  $pg_args = array(
    "base" => $base,
    "format" => "/",
    "total" => ceil($total_members / $per_page),
    "current" => max(1, $paged)
  );

  $pagination = Timber::get_pagination($pg_args);

  if (!empty($pagination["prev"])) {
    $pagination["prev"]["link"] = urldecode($pagination["prev"]["link"]);
    $pagination["prev"]["link"] = str_replace("/page/1", "", $pagination["prev"]["link"]);
  }

  if (!empty($pagination["next"])) {
    $pagination["next"]["link"] = urldecode($pagination["next"]["link"]);
  }

  $context["pagination"] = $pagination;
}

$context["posts"] = $members;
$context["wp_title"] = "Directory";
$context["industry_categories"] = get_field_object("field_596eb67cebf9f")["choices"];
$context["member_category"] = $member_role;
$context["companies_link"] = user_trailingslashit(get_site_url() . "/member-category/company");
$context["professionals_link"] = user_trailingslashit(get_site_url() . "/member-category/professional");

// Strip page number and search query from search form action
$search_action = $_SERVER["REQUEST_URI"];
$search_action = preg_replace("/\/page\/\d(\?s.+)?/i", "", $search_action);
$context["search_action"] = user_trailingslashit(get_site_url() . $search_action);

Timber::render("directory.twig", $context);
