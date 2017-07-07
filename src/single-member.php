<?php

global $params;

$context = Timber::get_context();

$member = new Timber\User($params["member_id"]);
$member_role = key($member->wp_capabilities);

$context["member"] = $member;
$context["wp_title"] = $member->name;

$templates = array("single-member-$member_role.twig", "single-member.twig");

Timber::render($templates, $context);
