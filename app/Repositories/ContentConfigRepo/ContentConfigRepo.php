<?php

namespace App\Repositories\ContentConfigRepo;

use App\Models\ContentConfig;
use App\Repositories\_Abstract\BaseRepository;

class ContentConfigRepo extends BaseRepository implements IContentConfigRepo
{
    protected $model;

    public function model()
    {
        return ContentConfig::class;
    }
}
