<?php

namespace App\Services;

class QRCodeService
{
    /**
     * Generate VietQR code URL for Vietnamese banking
     */
    public static function generateVietQRUrl($bankCode, $accountNumber, $amount, $description = '')
    {
        // Build the string for simple format: bank|account|amount|description
        $data = urlencode("$bankCode|$accountNumber|$amount|$description");
        
        // Return QR server URL
        return "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={$data}";
    }

    /**
     * Generate simple QR code for mobile banking
     * Format: BANK|ACCOUNT|AMOUNT|DESCRIPTION
     */
    public static function generateSimpleQR($bankCode, $accountNumber, $amount, $description = '')
    {
        $text = "{$bankCode}|{$accountNumber}|" . number_format($amount, 0, '', '') . "|{$description}";
        return "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" . urlencode($text);
    }
}
