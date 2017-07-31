<?php

global $params;

$context = Timber::get_context();
$post = Timber::get_post();

$has_date_slug = false;
if (isset($params["year"]) && isset($params["month"])) {
  $has_date_slug = true;
}

date_default_timezone_set(get_option("timezone_string"));
$time = strtotime(date("Y-m"));
if ($has_date_slug) {
  $year = $params["year"];
  $month = $params["month"];
  $time = strtotime("$year-$month");
}
$pagination_start_date = date("Y-m-d", $time);
$pagination_end_date = date("Y-m-t", $time);

// Pagination
$base_path = $_SERVER["REQUEST_URI"];
$base_path = preg_replace("/\/(\d+)\/(\d+)$/", "", $base_path);
$prev_date = date("Y/m", strtotime("-1 month", $time));
$next_date = date("Y/m", strtotime("+1 month", $time));
$pagination = array(
  "prev" => array(
    "link" => user_trailingslashit(get_site_url() . "$base_path/$prev_date")
  ),
  "next" => array(
    "link" => user_trailingslashit(get_site_url() . "$base_path/$next_date")
  )
);
$context["pagination"] = $pagination;
$context["pagination_name"] = "Month";

$query = array(
  "post_type" => "event",
  "posts_per_page" => -1,
  "meta_key" => "start_date",
  "orderby" => "meta_value",
  "order" => "ASC",
  "type" => "monthly",
  "meta_query" => array(
    "relation" => "OR",
    array(
      "relation" => "AND",
      array(
        "key" => "start_date",
        "value" => $pagination_start_date,
        "compare" => ">=",
        "type" => "DATE"
      ),
      array(
        "key" => "start_date",
        "value" => $pagination_end_date,
        "compare" => "<=",
        "type" => "DATE"
      )
    ),
    array(
      "relation" => "AND",
      array(
        "key" => "start_date",
        "value" => $pagination_end_date,
        "compare" => "<=",
        "type" => "DATE"
      ),
      array(
        "key" => "end_date",
        "value" => $pagination_start_date,
        "compare" => ">=",
        "type" => "DATE"
      )
    )
  )
);

// Category filter
if (isset($params["category"])) {
  $tax_query = array(
    "tax_query" => array(
      array(
        "taxonomy" => "event_category",
        "field" => "slug",
        "terms" => "expo",
      )
    )
  );
  $query = array_merge($query, $tax_query);
}

// Page title
if (!$has_date_slug) {
  $page_title = "Upcoming";
}
if (isset($params["category"])) {
  $category_object = get_term_by("slug", $params["category"], "event_category");
  $category_name = $category_object->name;
  $category_name = str_replace("Event", "", $category_name);
  $page_title .= " $category_name";
}
$page_title .= " Events";
if ($has_date_slug) {
  $page_title .= " in " . date("F, Y", $time);
}

$context["post"] = $post;
$context["posts"] = Timber::get_posts($query);
$context["wp_title"] = $page_title;

Timber::render("archive-event.twig", $context);
