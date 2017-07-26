<?php

global $params;

$context = Timber::get_context();
$post = Timber::get_post();

$has_date_slug = false;
if (isset($params["year"]) && isset($params["month"])) {
  $has_date_slug = true;
}

date_default_timezone_set(get_option("timezone_string"));
$time = time();
if ($has_date_slug) {
  $year = $params["year"];
  $month = $params["month"];
  $time = strtotime("$year-$month");
}
$pagination_start_date = date("Y-m-d", $time);
$pagination_end_date = date("Y-m-t", $time);

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
      "key" => "end_date",
      "value" => $pagination_start_date,
      "compare" => ">=",
      "type" => "DATE"
    )
  )
);

$context["post"] = $post;
$context["posts"] = Timber::get_posts($query);
$context["wp_title"] = "Upcoming Events";
if ($has_date_slug) {
  $context["wp_title"] = "Events in " . date("F, Y", $time);
}

Timber::render("archive-event.twig", $context);
