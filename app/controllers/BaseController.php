<?php

namespace app\controllers;
use app\models\Section;

class BaseController
{
    protected $sections;

    public function __construct()
    {
        $sectionModel = new Section();
        $this->sections = $sectionModel->getFrontSections();
    }

    protected function render(string $viewPath, array $data = []): void
    {
        $this->renderTemplate('front_office', $viewPath, $data);
    }

    protected function renderBackOffice(string $viewPath, array $data = []): void
    {
        $this->renderTemplate('back_office', $viewPath, $data);
    }

    private function renderTemplate(string $scope, string $viewPath, array $data = []): void
    {
        $baseViewPath = dirname(__DIR__) . '/views/' . $scope;
        $viewFile = $baseViewPath . '/pages/' . $viewPath . '.php';
        $layoutFile = $baseViewPath . '/layouts/main.php';

        if (!is_file($viewFile) || !is_file($layoutFile)) {
            http_response_code(500);
            echo 'Template not found.';
            return;
        }

        // Fusion des donnees
        if (!array_key_exists('sections', $data)) {
            $data['sections'] = $this->sections;
        }
        extract($data, EXTR_SKIP);

        // Capture du contenu de la vue
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // Inclusion du layout (qui utilise $content)
        require $layoutFile;
    }

    protected function json($payload, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function showNotFound(): void
    {
        $this->notFound();
    }

    protected function notFound(): void
    {
        $accept = strtolower((string) ($_SERVER['HTTP_ACCEPT'] ?? ''));
        $requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $isApiRequest = strncmp($requestPath, '/api/', 5) === 0;

        if ($isApiRequest || strpos($accept, 'application/json') !== false) {
            $this->json(['error' => 'Resource not found'], 404);
            return;
        }

        http_response_code(404);
        $this->render('Error/NotFound', [
            'title' => '404 - Page introuvable | IranWatch',
            'metaDescription' => 'La page demandee est introuvable.',
            'robots' => 'noindex, follow',
            'currentPage' => '',
        ]);
    }
}
