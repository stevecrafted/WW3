<?php

declare(strict_types=1);

namespace app\utils;

class ImageUploader
{
    private string $absoluteDirectory;
    private string $publicPrefix;

    public function __construct(string $relativeDirectory = 'uploads/contents')
    {
        $projectRoot = dirname(__DIR__, 2);
        $this->absoluteDirectory = $projectRoot . '/public/' . trim($relativeDirectory, '/');
        $this->publicPrefix = '/' . trim($relativeDirectory, '/');

        if (!is_dir($this->absoluteDirectory)) {
            mkdir($this->absoluteDirectory, 0775, true);
        }
    }

    public function uploadMany(array $files, array $altTexts = [], array $orders = []): array
    {
        $normalized = $this->normalizeFiles($files);
        $uploaded = [];

        foreach ($normalized as $index => $file) {
            if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                continue;
            }

            if (!is_uploaded_file($file['tmp_name'])) {
                continue;
            }

            $extension = $this->resolveSafeExtension((string) ($file['name'] ?? ''));
            $filename = uniqid('content_', true) . '.' . $extension;
            $targetPath = $this->absoluteDirectory . '/' . $filename;

            if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
                continue;
            }

            $order = (int) ($orders[$index] ?? 0);
            $order = $order > 0 ? $order : ($index + 1);

            $uploaded[] = [
                'url' => $this->publicPrefix . '/' . $filename,
                'alt_text' => trim((string) ($altTexts[$index] ?? '')),
                'display_order' => $order,
            ];
        }

        return $uploaded;
    }

    private function normalizeFiles(array $files): array
    {
        $names = $files['name'] ?? [];
        $tmpNames = $files['tmp_name'] ?? [];
        $errors = $files['error'] ?? [];
        $types = $files['type'] ?? [];
        $sizes = $files['size'] ?? [];

        if (!is_array($names)) {
            return [];
        }

        $normalized = [];
        foreach ($names as $index => $name) {
            $normalized[] = [
                'name' => (string) $name,
                'tmp_name' => (string) ($tmpNames[$index] ?? ''),
                'error' => (int) ($errors[$index] ?? UPLOAD_ERR_NO_FILE),
                'type' => (string) ($types[$index] ?? ''),
                'size' => (int) ($sizes[$index] ?? 0),
            ];
        }

        return $normalized;
    }

    private function resolveSafeExtension(string $filename): string
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($extension, $allowed, true)) {
            return 'jpg';
        }

        return $extension;
    }
}
