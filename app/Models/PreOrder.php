<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreOrder extends Model
{
    protected $fillable = ['email', 'keyword', 'status'];
}
