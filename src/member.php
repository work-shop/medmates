<?php

global $params;

$context = Timber::get_context();

$member_id = $params["member_id"];
$member = new Timber\User($member_id);
$member->role = key($member->wp_capabilities);

$context["post"] = $member;
$context["wp_title"] = $member->name;
$context["member_id"] = $member_id;

$templates = array("member-$member->role.twig", "member.twig");

Timber::render($templates, $context);
