<?php

namespace App\Services\ScheduleConfigService;

use App\Repositories\ScheduleConfig\IScheduleConfigRepo;
use App\Services\_Abstract\BaseService;
use App\Services\_Exception\AppServiceException;

class ScheduleConfigService extends BaseService
{
    protected $mainRepo;
    function __construct(IScheduleConfigRepo $iScheduleConfigRepo)
    {
        $this->mainRepo = $iScheduleConfigRepo;

    }
    public function create($request) {
        return DbTransactions()->addCallBackJson(function () use ($request) {
            $input = $request->all();

            $record = $this->mainRepo->create($input);

            return $record;
        });
    }
    public function update($request, $id) {
        return DbTransactions()->addCallBackJson(function () use ($request, $id) {
            $record = $this->mainRepo->find($id);

            if (!$record) {
                throw new AppServiceException('Not Found');
            }

            $record->update($request->all());

            return $record;
        });
    }
}
