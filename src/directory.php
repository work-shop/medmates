<?php

global $params;

$context = Timber::get_context();

$user_type = $params["user_type"];
$per_page = $params["per_page"];
$paged = 0;

if (isset($params["page_number"])) {
  $paged = $params["page_number"];
}

set_query_var("paged", $paged);

$args = array(
  "offset" => $paged ? ($paged - 1) * $per_page : 0,
  "number" => $per_page,
  "role" => $user_type,
  "meta_key" => "last_name",
  "orderby" => "meta_value",
  "order" => "ASC"
);

$users = get_users($args);

// Add a permalink to each user object
for ($i = 0; $i < count($users); $i++) {
  $user = $users[$i];
  $uri = $_SERVER["REQUEST_URI"];
  $baseUri = substr($uri, 0, strpos($uri, "/", strpos($uri, "/") + 1));
  $user->link = get_site_url() . "$baseUri/$user->ID/";
  $users[$i] = $user;
}

// Get total count of users (of $user_type)
$total_users = count_users();
$total_users = $total_users["avail_roles"][$user_type];

if ($total_users > $per_page) {
  $big = 999999999;
  $base = str_replace($big, "%#%", esc_url(get_pagenum_link($big)));
  $pg_args = array(
    "base" => $base,
    "total" => ceil($total_users / $per_page),
    "current" => max(1, $paged)
  );

  $context["pagination"] = Timber::get_pagination($pg_args);
}

$context["users"] = $users;
$context["wp_title"] = "Directory";

Timber::render("directory.twig", $context);
