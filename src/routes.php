<?php

// Helper Functions

function get_total_members_of_type($member_type) {
  $total_members = count_users();
  $total_members = $total_members["avail_roles"][$member_type];
  return $total_members;
}

function get_total_pages_of_members_of_type($member_type, $per_page) {
  $total_members = get_total_members_of_type($member_type);
  $total_pages = ceil($total_members / $per_page);
  return $total_pages;
}

function is_page_number_valid($page_number, $member_type, $per_page) {
  $total_pages = get_total_pages_of_members_of_type($member_type, $per_page);
  $bool = (($page_number > 0) && ($page_number <= $total_pages)) ? true : false;
  return $bool;
}


// Routes

Routes::map("directory", function ($params) {
  $params["member_type"] = "subscriber";
  $params["per_page"] = get_option("posts_per_page");

  if (is_page_number_valid(1, $params["member_type"], $params["per_page"])) {
    Routes::load("directory.php", $params, null, 200);
  } else {
    Routes::load("404.php", null, null, 404);
  }
});

Routes::map("directory/page/:page_number", function ($params) {
  $params["member_type"] = "subscriber";
  $params["per_page"] = get_option("posts_per_page");

  if (is_page_number_valid($params["page_number"], $params["member_type"], $params["per_page"])) {
    Routes::load("directory.php", $params, null, 200);
  } else {
    Routes::load("404.php", null, null, 404);
  }
});

Routes::map("directory/professionals/page/:page_number", function ($params) {
  $params["member_type"] = "subscriber";
  $params["per_page"] = get_option("posts_per_page");

  if (is_page_number_valid($params["page_number"], $params["member_type"], $params["per_page"])) {
    Routes::load("directory.php", $params, null, 200);
  } else {
    Routes::load("404.php", null, null, 404);
  }
});

Routes::map("directory/:member_id", function ($params) {
  Routes::load("member.php", $params, null, 200);
});
