<?php

declare(strict_types=1);

namespace app\utils;

class FileCache
{
    private string $cacheDir;

    public function __construct(?string $cacheDir = null)
    {
        $projectRoot = dirname(__DIR__, 2);
        $this->cacheDir = $cacheDir ?? ($projectRoot . '/cache');

        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0775, true);
        }
    }

    public function remember(string $key, int $ttlSeconds, callable $resolver)
    {
        $safeKey = $this->safeKey($key);
        $filePath = $this->cacheDir . '/' . $safeKey . '.cache';

        if (is_file($filePath)) {
            $content = @file_get_contents($filePath);
            $payload = $content !== false ? @unserialize($content) : false;

            if (is_array($payload) && isset($payload['expires_at']) && array_key_exists('value', $payload)) {
                if ((int) $payload['expires_at'] >= time()) {
                    return $payload['value'];
                }
            }
        }

        $value = $resolver();
        $payload = [
            'expires_at' => time() + $ttlSeconds,
            'value' => $value,
        ];

        @file_put_contents($filePath, serialize($payload), LOCK_EX);

        return $value;
    }

    /**
     * Supprime tous les fichiers de cache dont la clé commence par un préfixe donné.
     */
    public function forgetByPrefix(string $prefix): void
    {
        $safePrefix = $this->safeKey($prefix);
        $pattern = $this->cacheDir . '/' . $safePrefix . '*.cache';
        $files = glob($pattern);
        if ($files === false) {
            return;
        }
        foreach ($files as $file) {
            if (is_file($file)) {
                @unlink($file);
            }
        }
    }

    private function safeKey(string $key): string
    {
        return preg_replace('/[^a-zA-Z0-9_\-]/', '_', $key) ?? 'cache_key';
    }
}
