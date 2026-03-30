<?php

declare(strict_types=1);

date_default_timezone_set('UTC');

$projectRoot = dirname(__DIR__);
$configPath = $projectRoot . '/app/config/config.php';

if (!is_file($configPath)) {
  http_response_code(500);
  echo 'Config file not found in app/config/config.php';
  exit;
}

require $configPath;

spl_autoload_register(static function (string $class) use ($projectRoot): void {
  $prefix = 'app\\';
  if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
    return;
  }

  $relative = substr($class, strlen($prefix));
  $file = $projectRoot . '/app/' . str_replace('\\', '/', $relative) . '.php';
  if (is_file($file)) {
    require $file;
  }
});

set_exception_handler(static function (Throwable $exception): void {
  http_response_code(500);
  error_log((string) $exception);
  echo 'Internal Server Error';
});

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$path = rtrim($path, '/');
$path = $path === '' ? '/' : $path;
$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

$actualiteController = new app\controllers\ActualiteController();
$histoireController = new app\controllers\HistoireController();
$apiController = new app\controllers\ApiExampleController();
$baseController = new app\controllers\BaseController();

if ($method === 'GET' && ($path === '/' || $path === '/actualite')) {
  $actualiteController->index();
  exit;
}

if ($method === 'GET' && preg_match('#^/actualite/([^/]+)$#', $path, $matches)) {
  $actualiteController->show($matches[1]);
  exit;
}

if ($method === 'GET' && $path === '/histoire') {
  $histoireController->index();
  exit;
}

if ($method === 'GET' && preg_match('#^/histoire/([^/]+)$#', $path, $matches)) {
  $histoireController->show($matches[1]);
  exit;
}

if ($method === 'GET' && $path === '/api/users') {
  $apiController->getUsers();
  exit;
}

if ($method === 'GET' && preg_match('#^/api/users/(\d+)$#', $path, $matches)) {
  $apiController->getUser((int) $matches[1]);
  exit;
}

if ($method === 'POST' && preg_match('#^/api/users/(\d+)$#', $path, $matches)) {
  $apiController->updateUser((int) $matches[1]);
  exit;
}

$baseController->showNotFound();