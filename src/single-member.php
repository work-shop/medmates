<?php

global $params;
global $wp_roles;

$context = Timber::get_context();
$member = new Timber\User($params["member_id"]);

$context["member"] = $member;
$context["wp_title"] = $member->name;

$member_role = key($member->wp_capabilities);
$context["member_category"] = $wp_roles->roles[$member_role]["name"]; // Role display name

Timber::render("single-member.twig", $context);
