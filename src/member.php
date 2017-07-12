<?php

global $params;

$context = Timber::get_context();
$member = new Timber\User($params["member_id"]);
$member->role = key($member->wp_capabilities);

$context["post"] = $member;
$context["wp_title"] = $member->name;

$templates = array("member-$member->role.twig", "member.twig");

Timber::render($templates, $context);
