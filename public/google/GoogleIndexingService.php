<?php

namespace App\Services;

use Google_Client;
use Google_Service_Indexing;

class GoogleIndexingService
{
    protected $client;
    protected $service;

    public function __construct()
    {
        $keyFile = public_path('google/dungthu-485212-5c8ae6075c55.json');
        $this->client = new \Google_Client();
        $this->client->setAuthConfig($keyFile);
        $this->client->addScope(\Google_Service_Indexing::INDEXING);
        $this->service = new \Google_Service_Indexing($this->client);
    }

    /**
     * Submit a URL to Google Indexing API
     * @param string $url
     * @param string $type 'URL_UPDATED' | 'URL_DELETED'
     * @return array
     */
    public function publishUrl($url, $type = 'URL_UPDATED')
    {
        $postBody = new \Google_Service_Indexing_UrlNotification();
        $postBody->setType($type);
        $postBody->setUrl($url);
        try {
            $result = $this->service->urlNotifications->publish($postBody);
            return ['success' => true, 'result' => $result];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    // Static helper để gọi nhanh không cần khởi tạo thủ công
    public static function publishUrlStatic($url, $type = 'URL_UPDATED')
    {
        $service = new self();
        return $service->publishUrl($url, $type);
    }
}
