<?php

$context = Timber::get_context();
$context["post"] = Timber::get_post();

$templates = array(
  "archive.twig",
  "index.twig"
);

Timber::render($templates, $context);
