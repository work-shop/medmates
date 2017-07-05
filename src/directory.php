<?php

$context = Timber::get_context();
$context["members"] = get_users();

Timber::render("directory.twig", $context);
