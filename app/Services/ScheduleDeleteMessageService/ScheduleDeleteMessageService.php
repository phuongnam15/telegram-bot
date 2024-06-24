<?php

namespace App\Services\ScheduleDeleteMessageService;

use App\Models\ScheduleDeleteMessage;
use App\Services\_Abstract\BaseService;

class ScheduleDeleteMessageService extends BaseService
{
    public function get()
    {
        $a = ScheduleDeleteMessage::first();

        if (!$a) {
            return response()->json('Not found config');
        }
        
        return response()->json($a);
    }
    public function update($request)
    {
        $a = ScheduleDeleteMessage::first();

        if (!$a) {
            return response()->json('Not found config');
        }

        $a->update($request->all());

        return response()->json($a);
    }
}
