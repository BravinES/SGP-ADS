<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CacheQueryDB extends Model
{
    protected $table = 'system__cache_query_db';
    use HasFactory;
}
