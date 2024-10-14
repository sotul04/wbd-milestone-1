<?php

define("BASE_URL","http://localhost:8000");

// DATABASE
define('DB_HOST', $_ENV['DB_HOST']);
define('DB_PORT', $_ENV['DB_PORT']);
define('DB_DATABASE', $_ENV['DB_DATABASE']);
define('DB_USERNAME', $_ENV['DB_USERNAME']);
define('DB_PASSWORD', $_ENV['DB_PASSWORD']);

define('ROWS_PER_PAGE', 8);

// LOGIN API METHOD
define('NOT_LOGGED_IN', 'not_logged_in');
define('ALREADY_LOGIN', 'already_in');
define('ACCOUNT_NOT_FOUND', 'account_not_found');