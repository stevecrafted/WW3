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

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

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

if ($method === 'GET' && isset($_GET['id']) && ctype_digit((string) $_GET['id']) && ($path === '/actualite' || $path === '/histoire')) {
  $contenuModel = new app\models\Contenu();
  $sectionModel = new app\models\Section();
  $article = $contenuModel->findOne(['id' => (int) $_GET['id'], 'deleted_at' => null]);

  if ($article) {
    $articleSection = $sectionModel->findOne(['id' => $article->section_id, 'deleted_at' => null]);
    if ($articleSection && in_array($articleSection->slug, ['actualite', 'histoire'], true)) {
      header('Location: /' . $articleSection->slug . '/' . $article->id . '-' . $article->slug, true, 301);
      exit;
    }
  }
}

$actualiteController = new app\controllers\ActualiteController();
$histoireController = new app\controllers\HistoireController();
$apiController = new app\controllers\ApiExampleController();
$baseController = new app\controllers\BaseController();
$authController = new app\controllers\AuthController();
$sectionAdminController = new app\controllers\SectionAdminController();
$contentAdminController = new app\controllers\ContentAdminController();

if ($method === 'GET' && $path === '/') {
  $authController->home();
  exit;
}

if ($method === 'GET' && $path === '/login') {
  $authController->showLogin();
  exit;
}

if ($method === 'POST' && $path === '/login') {
  $authController->login();
  exit;
}

if ($method === 'POST' && $path === '/logout') {
  $authController->logout();
  exit;
}

if (strncmp($path, '/admin/', 7) === 0) {
  $isAuthenticated = isset($_SESSION['auth_user']) && is_array($_SESSION['auth_user']);
  if (!$isAuthenticated) {
    header('Location: /login');
    exit;
  }
}

if ($method === 'GET' && $path === '/actualite') {
  $actualiteController->index();
  exit;
}

if ($method === 'GET' && preg_match('#^/actualite/(\d+)(?:-([^/]+))?$#', $path, $matches)) {
  $actualiteController->show((int) $matches[1], $matches[2] ?? null);
  exit;
}

if ($method === 'GET' && $path === '/histoire') {
  $histoireController->index();
  exit;
}

if ($method === 'GET' && preg_match('#^/histoire/(\d+)(?:-([^/]+))?$#', $path, $matches)) {
  $histoireController->show((int) $matches[1], $matches[2] ?? null);
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

if ($method === 'GET' && $path === '/admin/sections') {
  $sectionAdminController->index();
  exit;
}

if ($method === 'POST' && $path === '/admin/sections/save') {
  $sectionAdminController->save();
  exit;
}

if ($method === 'POST' && $path === '/admin/sections/delete') {
  $sectionAdminController->delete();
  exit;
}

if ($method === 'GET' && preg_match('#^/admin/sections/(\d+)/contents$#', $path, $matches)) {
  $contentAdminController->index((int) $matches[1]);
  exit;
}

if ($method === 'GET' && preg_match('#^/admin/sections/(\d+)/contents/(\d+)$#', $path, $matches)) {
  $contentAdminController->show((int) $matches[1], (int) $matches[2]);
  exit;
}

if ($method === 'GET' && preg_match('#^/admin/sections/(\d+)/contents/create$#', $path, $matches)) {
  $contentAdminController->createForm((int) $matches[1]);
  exit;
}

if ($method === 'GET' && preg_match('#^/admin/sections/(\d+)/contents/(\d+)/edit$#', $path, $matches)) {
  $contentAdminController->editForm((int) $matches[1], (int) $matches[2]);
  exit;
}

if ($method === 'POST' && preg_match('#^/admin/sections/(\d+)/contents/save$#', $path, $matches)) {
  $contentAdminController->save((int) $matches[1]);
  exit;
}

if ($method === 'POST' && preg_match('#^/admin/sections/(\d+)/contents/(\d+)/delete$#', $path, $matches)) {
  $contentAdminController->delete((int) $matches[1], (int) $matches[2]);
  exit;
}

$baseController->showNotFound();