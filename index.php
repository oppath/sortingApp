#!/usr/bin/env php
<?php
require_once __DIR__ . '/vendor/autoload.php';
use Symfony\Component\Console\Application;
use Console\SortingTranslation;
$app = new Application('Sorting Translation App', 'v1.0.0');
$app->add(new SortingTranslation());
$app->run();