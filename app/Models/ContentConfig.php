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
        'type',
        'kind'
    ];
    const KIND_INTRO = 'introduce';
    const TYPE_TEXT = 'text';
    const TYPE_PHOTO = 'photo';
    const TYPE_VIDEO = 'video';
    const MAP_TYPE = [
        self::TYPE_TEXT,
        self::TYPE_PHOTO,
        self::TYPE_VIDEO,
    ];
}
