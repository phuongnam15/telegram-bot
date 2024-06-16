<?php

namespace App\Repositories\Admin;

use App\Models\AdminModel;
use App\Repositories\_Abstract\BaseRepository;

class AdminRepo extends BaseRepository
{
    protected $model;

    public function model()
    {
        return AdminModel::class;
    }
}
