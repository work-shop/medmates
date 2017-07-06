<?php

// Helper Functions

function get_total_members_of_type($member_role) {
  $total_members = count_users();
  $total_members = $total_members["avail_roles"][$member_role];
  return $total_members;
}

function get_total_pages_of_members_of_type($member_role, $per_page) {
  $total_members = get_total_members_of_type($member_role);
  $total_pages = ceil($total_members / $per_page);
  return $total_pages;
}

function is_page_number_valid($page_number, $member_role, $per_page) {
  $total_pages = get_total_pages_of_members_of_type($member_role, $per_page);
  $bool = (($page_number > 0) && ($page_number <= $total_pages)) ? true : false;
  return $bool;
}


// Routes

Routes::map("members", function ($params) {
  $params["member_role"] = "company";
  $params["per_page"] = get_option("posts_per_page");

  if (is_page_number_valid(1, $params["member_role"], $params["per_page"])) {
    Routes::load("directory.php", $params, null, 200);
  } else {
    Routes::load("404.php", null, null, 404);
  }
});

Routes::map("member/page/:page_number", function ($params) {
  $params["member_role"] = "company";
  $params["per_page"] = get_option("posts_per_page");

  if (is_page_number_valid($params["page_number"], $params["member_role"], $params["per_page"])) {
    Routes::load("directory.php", $params, null, 200);
  } else {
    Routes::load("404.php", null, null, 404);
  }
});

Routes::map("member-categories/company", function ($params) {
  $params["member_role"] = "company";
  $params["per_page"] = get_option("posts_per_page");

  if (is_page_number_valid(1, $params["member_role"], $params["per_page"])) {
    Routes::load("directory.php", $params, null, 200);
  } else {
    Routes::load("404.php", null, null, 404);
  }
});

Routes::map("member-categories/company/page/:page_number", function ($params) {
  $params["member_role"] = "company";
  $params["per_page"] = get_option("posts_per_page");

  if (is_page_number_valid($params["page_number"], $params["member_role"], $params["per_page"])) {
    Routes::load("directory.php", $params, null, 200);
  } else {
    Routes::load("404.php", null, null, 404);
  }
});

Routes::map("member-categories/professional", function ($params) {
  $params["member_role"] = "professional";
  $params["per_page"] = get_option("posts_per_page");

  if (is_page_number_valid(1, $params["member_role"], $params["per_page"])) {
    Routes::load("directory.php", $params, null, 200);
  } else {
    Routes::load("404.php", null, null, 404);
  }
});

Routes::map("member-categories/professional/page/:page_number", function ($params) {
  $params["member_role"] = "professional";
  $params["per_page"] = get_option("posts_per_page");

  if (is_page_number_valid($params["page_number"], $params["member_role"], $params["per_page"])) {
    Routes::load("directory.php", $params, null, 200);
  } else {
    Routes::load("404.php", null, null, 404);
  }
});

Routes::map("members/:member_id", function ($params) {
  $member = get_userdata($params["member_id"]);
  $valid_member_roles = ["professional", "company"];

  // If member exists and its role is a valid one, show the member page
  if ($member && (array_intersect((array) $member->roles, $valid_member_roles))) {
    Routes::load("single-member.php", $params, null, 200);
  } else {
    Routes::load("404.php", null, null, 404);
  }
});
