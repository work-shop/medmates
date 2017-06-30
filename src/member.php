<?php

global $params;

$context = Timber::get_context();
$context["member"] = new Timber\User($params["user_id"]);

Timber::render("member.twig", $context);
