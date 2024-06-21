<?php

namespace App\Services\ScheduleGroupConfigService;

use App\Models\ScheduleGroupConfig;
use App\Services\_Abstract\BaseService;
use App\Services\_Exception\AppServiceException;
use App\Services\_Trait\SaveFile;

class ScheduleGroupConfigService extends BaseService
{
    use SaveFile;

    public function configSchedule($request)
    {
        return DbTransactions()->addCallBackJson(function () use ($request) {
            $record = ScheduleGroupConfig::first();
            
            if(!$record){
                throw new AppServiceException('Seed data ?');
            }

            $record->update($request->all());

            return $record;
        });
    }
    public function getSchedule()
    {
        $record = ScheduleGroupConfig::first();

        if(!$record){
            return response()->json([
                'message' => 'Seed data ?'
            ]);
        }

        return response()->json($record);
    }
}
