<?php

$context = Timber::get_context();

$templates = array("404.twig");

Timber::render($templates, $context);
