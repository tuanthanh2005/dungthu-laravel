<?php

namespace App\Helpers;

use App\Models\SiteSetting;

class SupportHelper
{
    /**
     * Get support contact information
     */
    public static function getContactInfo()
    {
        return [
            'email' => self::getEmail(),
            'zalo' => self::getZaloNumber(),
            'phone' => self::getPhone(),
            'live_chat' => self::getLiveChat(),
        ];
    }

    /**
     * Get Facebook Fanpage Link
     */
    public static function getFacebookLink()
    {
        return SiteSetting::getValue('support_facebook_link', 'https://www.facebook.com/profile.php?id=61589359706008');
    }

    /**
     * Get Zalo Chat Link
     */
    public static function getZaloLink()
    {
        return SiteSetting::getValue('support_zalo_link', 'https://zalo.me/0772698113');
    }

    /**
     * Get Zalo Number
     */
    public static function getZaloNumber()
    {
        return SiteSetting::getValue('support_zalo_number', '0772698113');
    }

    /**
     * Get Telegram Link
     */
    public static function getTelegramLink()
    {
        return SiteSetting::getValue('support_telegram_link', 'https://t.me/specademy');
    }

    /**
     * Get Telegram Username
     */
    public static function getTelegramUsername()
    {
        return SiteSetting::getValue('support_telegram_username', '@specademy');
    }

    /**
     * Get email
     */
    public static function getEmail()
    {
        return SiteSetting::getValue('support_email', 'tranthanhtuanfix@gmail.com');
    }

    /**
     * Get phone
     */
    public static function getPhone()
    {
        return SiteSetting::getValue('support_phone', '0772698113');
    }

    /**
     * Get live chat URL (or custom support link if any)
     */
    public static function getLiveChat()
    {
        return SiteSetting::getValue('support_live_chat_url', '#');
    }
}

