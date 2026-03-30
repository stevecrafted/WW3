<?php

namespace app\controllers;

class BaseController
{
    protected function render(string $viewPath, array $data = []): void
    {
        $baseViewPath = dirname(__DIR__) . '/views/front_office';
        $viewFile = $baseViewPath . '/pages/' . $viewPath . '.php';
        $layoutFile = $baseViewPath . '/layouts/main.php';

        if (!is_file($viewFile) || !is_file($layoutFile)) {
            http_response_code(500);
            echo 'Template not found.';
            return;
        }

        extract($data, EXTR_SKIP);

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        require $layoutFile;
    }

    protected function json($payload, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    protected function notFound(): void
    {
        http_response_code(404);
        echo '404 - Page not found';
    }
}
