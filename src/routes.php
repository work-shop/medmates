<?php

Routes::map("/", function ($params) {
  $args = array(
    "post_type" => "post",
    "posts_per_page" => 3
  );
  $params["posts"] = Timber::get_posts($args);
  Routes::load("page.php", $params, null, 200);
});

Routes::map("home", function ($params) {
  Routes::load("404.php", null, null, 404);
});

Routes::map("search", function ($params) {
  Routes::load("search.php", $params, null, 200);
});

Routes::map("active-teams", function ($params) {
  $args = array(
    "post_type" => "active_team",
    "posts_per_page" => -1,
    "orderby" => "title",
    "order" => "ASC"
  );
  $params["posts"] = Timber::get_posts($args);
  Routes::load("page.php", $params, null, 200);
});

Routes::map("events/:year/:month", function ($params) {
  Routes::load("archive-event.php", $params, null, 200);
});

Routes::map("event-category/:category", function ($params) {
  Routes::load("archive-event.php", $params, null, 200);
});

Routes::map("event-category/:category/:year/:month", function ($params) {
  Routes::load("archive-event.php", $params, null, 200);
});

Routes::map("resources", function ($params) {
  $args = array(
    "post_type" => "resource",
    "posts_per_page" => -1,
    "orderby" => "title",
    "order" => "ASC"
  );
  $params["posts"] = Timber::get_posts($args);
  Routes::load("archive.php", $params, null, 200);
});

Routes::map("members", function ($params) {
  Routes::load("directory.php", $params, null, 200);
});

Routes::map("members/search/:s", function ($params) {
  Routes::load("directory.php", $params, null, 200);
});

Routes::map("members/page/:page_number", function ($params) {
  Routes::load("directory.php", $params, null, 200);
});

Routes::map("members/search/:s/page/:page_number", function ($params) {
  Routes::load("directory.php", $params, null, 200);
});

Routes::map("member-category/:member_role", function ($params) {
  Routes::load("directory.php", $params, null, 200);
});

Routes::map("member-category/:member_role/search/:s", function ($params) {
  Routes::load("directory.php", $params, null, 200);
});

Routes::map("member-category/:member_role/page/:page_number", function ($params) {
  Routes::load("directory.php", $params, null, 200);
});

Routes::map("member-category/:member_role/search/:s/page/:page_number", function ($params) {
  Routes::load("directory.php", $params, null, 200);
});

Routes::map("member/:member_id", function ($params) {
  $member = get_userdata($params["member_id"]);
  $valid_member_roles = array("individual", "company");

  // If member exists and its role is a valid one, show the member page
  if ($member
      && (array_intersect((array) $member->roles, $valid_member_roles))
      && $member->member_approval === "approved") {
    Routes::load("member.php", $params, null, 200);
  } else {
    Routes::load("404.php", null, null, 404);
  }
});
