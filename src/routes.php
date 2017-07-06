<?php

// Helper Functions

function get_total_users_of_type($user_type) {
  $total_users = count_users();
  $total_users = $total_users["avail_roles"][$user_type];
  return $total_users;
}

function get_total_pages_of_users_of_type($user_type, $per_page) {
  $total_users = get_total_users_of_type($user_type);
  $total_pages = ceil($total_users / $per_page);
  return $total_pages;
}

function is_page_number_valid($page_number, $user_type, $per_page) {
  $total_pages = get_total_pages_of_users_of_type($user_type, $per_page);
  $bool = (($page_number > 0) && ($page_number <= $total_pages)) ? true : false;
  return $bool;
}


// Routes

Routes::map("directory", function ($params) {
  $params["user_type"] = "subscriber";
  $params["per_page"] = get_option("posts_per_page");

  if (is_page_number_valid(1, $params["user_type"], $params["per_page"])) {
    Routes::load("directory.php", $params, null, 200);
  } else {
    Routes::load("404.php", null, null, 404);
  }
});

Routes::map("directory/page/:page_number", function ($params) {
  $params["user_type"] = "subscriber";
  $params["per_page"] = get_option("posts_per_page");

  if (is_page_number_valid($params["page_number"], $params["user_type"], $params["per_page"])) {
    Routes::load("directory.php", $params, null, 200);
  } else {
    Routes::load("404.php", null, null, 404);
  }
});

Routes::map("directory/professionals/page/:page_number", function ($params) {
  $params["user_type"] = "subscriber";
  $params["per_page"] = get_option("posts_per_page");

  if (is_page_number_valid($params["page_number"], $params["user_type"], $params["per_page"])) {
    Routes::load("directory.php", $params, null, 200);
  } else {
    Routes::load("404.php", null, null, 404);
  }
});

Routes::map("directory/:user_id", function ($params) {
  Routes::load("user.php", $params, null, 200);
});
