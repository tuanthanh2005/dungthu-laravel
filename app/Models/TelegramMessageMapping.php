<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramMessageMapping extends Model
{
    protected $fillable = [
        'telegram_message_id',
        'telegram_chat_id',
        'type',
        'related_id',
    ];
}
