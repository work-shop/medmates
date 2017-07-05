<?php

$context = Timber::get_context();
$context["members"] = get_users();
$context["wp_title"] = "Directory";

Timber::render("directory.twig", $context);
