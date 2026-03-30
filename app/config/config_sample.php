<?php

date_default_timezone_set('UTC');
error_reporting(E_ALL);

if (function_exists('mb_internal_encoding') === true) {
	mb_internal_encoding('UTF-8');
}

if (function_exists('setlocale') === true) {
	setlocale(LC_ALL, 'en_US.UTF-8');
}

return [
	'database' => [
		'host' => 'localhost',
		'dbname' => 'your_db_name',
		'user' => 'your_username',
		'password' => 'your_password',
		'charset' => 'utf8mb4', 
	],
];
