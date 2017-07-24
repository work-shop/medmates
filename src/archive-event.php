<?php

global $params;
var_dump("hi");

$context = Timber::get_context();
$post = Timber::get_post();

$is_paginated = false;
if (isset($params["year"]) && isset($params["month"])) {
  $is_paginated = true;
}

date_default_timezone_set(get_option("timezone_string"));
$time = time();
if ($is_paginated) {
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
if ($is_paginated) {
  $context["wp_title"] = "Events in " . date("F, Y", $time);
}

Timber::render("archive-event.twig", $context);
