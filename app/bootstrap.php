<?php

// Load our Composer components.
require __DIR__ . '/../vendor/autoload.php';

// Load our helpers.
require __DIR__ . '/helpers.php';

// Load our Dotenv variables.
$dotenv = new \Dotenv\Dotenv(__DIR__ . '/../');
$dotenv->load();

// Load any other classes or such that need special configuration.
require __DIR__ . '/bootstrap/database.php';

// Load our Slim config and booter.
require __DIR__ . '/bootstrap/slim.php';