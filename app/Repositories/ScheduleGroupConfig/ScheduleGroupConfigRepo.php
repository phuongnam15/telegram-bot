<?php

namespace App\Repositories\ScheduleGroupConfig;

use App\Models\ScheduleGroupConfig;
use App\Repositories\_Abstract\BaseRepository;

class ScheduleGroupConfigRepo extends BaseRepository implements IScheduleGroupConfigRepo
{
    protected $model;

    public function model()
    {
        return ScheduleGroupConfig::class;
    }
}
