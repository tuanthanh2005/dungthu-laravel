<?php

namespace App\Services;

class GoogleIndexingService
{
    protected $service;

    private function resolveKeyFilePath(?string $keyFilePath): ?string
    {
        if (!$keyFilePath) {
            return null;
        }

        // Accept both absolute and project-relative paths from env/config.
        if (is_file($keyFilePath)) {
            return $keyFilePath;
        }

        $relativePath = ltrim($keyFilePath, '/\\');
        $projectPath = base_path($relativePath);

        if (is_file($projectPath)) {
            return $projectPath;
        }

        return null;
    }

    public function __construct(?string $keyFile = null)
    {
        $keyFilePath = $this->resolveKeyFilePath($keyFile ?: config('services.google_indexing.key_file'));

        if (!$keyFilePath || !is_file($keyFilePath)) {
            throw new \RuntimeException('Google Indexing key file not found.');
        }

        $client = new \Google_Client();
        $client->setAuthConfig($keyFilePath);
        $client->addScope(\Google_Service_Indexing::INDEXING);

        $this->service = new \Google_Service_Indexing($client);
    }

    /**
     * Submit a URL to Google Indexing API.
     */
    public function publishUrl(string $url, string $type = 'URL_UPDATED'): array
    {
        $postBody = new \Google_Service_Indexing_UrlNotification();
        $postBody->setType($type);
        $postBody->setUrl($url);

        try {
            $result = $this->service->urlNotifications->publish($postBody);

            \Log::info('Google Indexing submitted', [
                'url' => $url,
                'type' => $type,
            ]);

            return ['success' => true, 'result' => $result];
        } catch (\Throwable $e) {
            \Log::error('Google Indexing submit failed', [
                'url' => $url,
                'type' => $type,
                'message' => $e->getMessage(),
            ]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public static function publishUrlStatic(string $url, string $type = 'URL_UPDATED'): array
    {
        try {
            $service = new self();

            return $service->publishUrl($url, $type);
        } catch (\Throwable $e) {
            \Log::error('Google Indexing service init failed', [
                'url' => $url,
                'type' => $type,
                'message' => $e->getMessage(),
            ]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
