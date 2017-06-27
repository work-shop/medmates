<?php

$context = array();
$context["widgets"] = Timber::get_widgets("dynamic_sidebar");

$templates = array("sidebar.twig");

Timber::render($templates, $context);
