<?php

Routes::map("/", function ($params) {
  $params["posts"] = Timber::get_posts("post_type=post&numberposts=5");
  Routes::load("page.php", $params, null, 200);
});

Routes::map("home", function ($params) {
  Routes::load("404.php", null, null, 404);
});

Routes::map("search", function ($params) {
  Routes::load("search.php", $params, null, 200);
});

Routes::map("active-teams", function ($params) {
  $params["posts"] = Timber::get_posts("post_type=active_team&numberposts=-1&orderby=title&order=ASC");
  Routes::load("page.php", $params, null, 200);
});

Routes::map("events", function ($params) {
  $params["posts"] = Timber::get_posts("post_type=event&numberposts=-1&orderby=title&order=ASC");
  Routes::load("page.php", $params, null, 200);
});

Routes::map("resources", function ($params) {
  $params["posts"] = Timber::get_posts("post_type=resource&numberposts=-1&orderby=title&order=ASC");
  Routes::load("page.php", $params, null, 200);
});

Routes::map("members", function ($params) {
  Routes::load("directory.php", $params, null, 200);
});

Routes::map("members/page/:page_number", function ($params) {
  Routes::load("directory.php", $params, null, 200);
});

Routes::map("member-category/:member_role", function ($params) {
  Routes::load("directory.php", $params, null, 200);
});

Routes::map("member-category/:member_role/page/:page_number", function ($params) {
  Routes::load("directory.php", $params, null, 200);
});

Routes::map("member/:member_id", function ($params) {
  $member = get_userdata($params["member_id"]);
  $valid_member_roles = array("professional", "company");

  // If member exists and its role is a valid one, show the member page
  if ($member && (array_intersect((array) $member->roles, $valid_member_roles))) {
    Routes::load("member.php", $params, null, 200);
  } else {
    Routes::load("404.php", null, null, 404);
  }
});
