<?php

$context = Timber::get_context();
$post = Timber::get_post();
$team_members = get_field("team_members");
$filtered_team_members = [];

// Add a permalink to each member object
for ($i = 0; $i < count($team_members); $i++) {
  $member = new Timber\User($team_members[$i]["ID"]); // Fetch member object so we have avatar data
  if ($member->member_approval === "approved") {
    $member->link = user_trailingslashit(get_site_url() . "/member/$member->ID");
    $filtered_team_members[$i] = $member;
  }
}

$context["post"] = $post;
$context["team_members"] = $filtered_team_members;

$templates = array("single-$post->post_type.twig", "single.twig");

Timber::render($templates, $context);
