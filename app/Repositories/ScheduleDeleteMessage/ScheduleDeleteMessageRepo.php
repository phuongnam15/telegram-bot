<?php

namespace App\Repositories\ScheduleDeleteMessage;

use App\Models\ScheduleDeleteMessage;
use App\Repositories\_Abstract\BaseRepository;

class ScheduleDeleteMessageRepo extends BaseRepository implements IScheduleDeleteMessageRepo
{
    protected $model;

    public function model()
    {
        return ScheduleDeleteMessage::class;
    }
}
