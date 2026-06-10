<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proxy extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'protocol',
        'ip',
        'port',
        'username',
        'password',
        'price',
        'duration',
        'is_active',
        'description',
    ];
}
