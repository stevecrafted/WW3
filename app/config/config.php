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
		'host' => getenv('DB_HOST') ?: 'localhost',
		'dbname' => getenv('DB_NAME') ?: 'ww3',
		'user' => getenv('DB_USER') ?: 'root',
		'password' => getenv('DB_PASSWORD') ?: '',
		'charset' => 'utf8mb4',
		// 'file_path' => __DIR__ . '/../../db/database.sqlite',
	],
];
