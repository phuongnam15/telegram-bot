<?php

namespace App\Repositories\ScheduleConfig;

use App\Models\ScheduleConfig;
use App\Repositories\_Abstract\BaseRepository;

class ScheduleConfigRepo extends BaseRepository implements IScheduleConfigRepo
{
    protected $model;

    public function model()
    {
        return ScheduleConfig::class;
    }
}
