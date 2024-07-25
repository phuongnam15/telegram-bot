<?php

namespace App\Services\ScheduleGroupConfigService;

use App\Repositories\ScheduleGroupConfig\IScheduleGroupConfigRepo;
use App\Services\_Abstract\BaseService;
use App\Services\_Exception\AppServiceException;

class ScheduleGroupConfigService extends BaseService
{
    protected $mainRepo;
    function __construct(IScheduleGroupConfigRepo $iScheduleGroupConfigRepo)
    {
        $this->mainRepo = $iScheduleGroupConfigRepo;
    }
    public function create($request)
    {
        return DbTransactions()->addCallBackJson(function () use ($request) {
            $input = $request->all();

            $record = $this->mainRepo->create($input);

            return $record;
        });
    }
    public function update($request, $id)
    {
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
