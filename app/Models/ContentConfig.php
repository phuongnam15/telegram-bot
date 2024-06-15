<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentConfig extends Model
{
    use HasFactory;
    protected $table = 'content_configs';
    protected $fillable = [
        'media',
        'buttons',
        'content',
        'type'
    ];
}
