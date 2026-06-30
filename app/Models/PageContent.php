<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageContent extends Model
{
    protected $fillable = ['slug', 'title', 'subtitle', 'content_json', 'is_active'];

    protected $casts = [
        'content_json' => 'array',
        'is_active' => 'boolean',
    ];
}
