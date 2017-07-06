<?php

global $params;

$context = Timber::get_context();

$member_role = $params["member_role"];
$per_page = $params["per_page"];
$paged = 0;

if (isset($params["page_number"])) {
  $paged = $params["page_number"];
}

set_query_var("paged", $paged);

$args = array(
  "offset" => $paged ? ($paged - 1) * $per_page : 0,
  "number" => $per_page,
  "role" => $member_role,
  "meta_key" => "last_name",
  "orderby" => "meta_value",
  "order" => "ASC"
);

$members = get_users($args);

// Add a permalink to each member object
for ($i = 0; $i < count($members); $i++) {
  $member = new Timber\User($members[$i]->ID); // Fetch member object so we have avatar data
  $member->link = user_trailingslashit(get_site_url() . "/members/$member->ID");
  $members[$i] = $member;
}

// Get total count of members (of $member_role)
$total_members = count_users();
$total_members = $total_members["avail_roles"][$member_role];

if ($total_members > $per_page) {
  $big = 999999999;
  $base = str_replace($big, "%#%", esc_url(get_pagenum_link($big)));
  $pg_args = array(
    "base" => $base,
    "total" => ceil($total_members / $per_page),
    "current" => max(1, $paged)
  );

  $context["posts"]["pagination"] = Timber::get_pagination($pg_args);
}

$context["members"] = $members;
$context["wp_title"] = "Directory";
$context["companies_link"] = user_trailingslashit(get_site_url() . "/member-categories/company");
$context["professionals_link"] = user_trailingslashit(get_site_url() . "/member-categories/professional");

Timber::render("directory.twig", $context);
