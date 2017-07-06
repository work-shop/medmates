<?php

global $params;

$context = Timber::get_context();
$user = new Timber\User($params["user_id"]);

// Check if user has role "company" or "individual"
// if (in_array("company", $user->roles)) {
//   $user->link = "$baseUri/$user->ID/";
// } elseif (in_array("subscriber", (array) $user->roles)) { // TODO: change "subscriber" to "individual"
//   $user->link = "$baseUri/$user->ID/";
// }

$context["user"] = $user;
$context["wp_title"] = $user->name;

Timber::render("user.twig", $context);
