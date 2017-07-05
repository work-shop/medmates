<?php

global $params;

$context = Timber::get_context();

$member = new Timber\User($params["user_id"]);
$context["member"] = $member;

$context["wp_title"] = $member->name;

Timber::render("member.twig", $context);
