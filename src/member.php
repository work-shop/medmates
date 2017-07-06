<?php

global $params;

$context = Timber::get_context();
$member = new Timber\User($params["member_id"]);

// Check if member has role "company" or "individual"
// if (in_array("company", $member->roles)) {
//   $member->link = "$baseUri/$member->ID/";
// } elseif (in_array("subscriber", (array) $member->roles)) { // TODO: change "subscriber" to "individual"
//   $member->link = "$baseUri/$member->ID/";
// }

$context["member"] = $member;
$context["wp_title"] = $member->name;

Timber::render("member.twig", $context);
