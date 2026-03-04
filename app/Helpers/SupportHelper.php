<?php

namespace App\Helpers;

class SupportHelper
{
    /**
     * Get support contact information
     */
    public static function getContactInfo()
    {
        return [
            'email' => env('SUPPORT_EMAIL', 'tranthanhtuanfix@gmail.com'),
            'zalo' => env('SUPPORT_ZALO', '0708910952'),
            'phone' => env('SUPPORT_PHONE', '0772698113'),
            'live_chat' => env('SUPPORT_LIVE_CHAT_URL', '#'),
        ];
    }

    /**
     * Get email
     */
    public static function getEmail()
    {
        return self::getContactInfo()['email'];
    }

    /**
     * Get zalo number
     */
    public static function getZalo()
    {
        return self::getContactInfo()['zalo'];
    }

    /**
     * Get phone
     */
    public static function getPhone()
    {
        return self::getContactInfo()['phone'];
    }

    /**
     * Get live chat URL
     */
    public static function getLiveChat()
    {
        return self::getContactInfo()['live_chat'];
    }
}
