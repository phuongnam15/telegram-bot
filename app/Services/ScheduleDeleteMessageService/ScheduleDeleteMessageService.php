<?php

namespace App\Services\ScheduleDeleteMessageService;

use App\Repositories\ScheduleDeleteMessage\IScheduleDeleteMessageRepo;
use App\Services\_Abstract\BaseService;
use App\Services\_Exception\AppServiceException;

class ScheduleDeleteMessageService extends BaseService
{
    protected $mainRepo;
    function __construct(IScheduleDeleteMessageRepo $iScheduleDeleteMessageRepo)
    {
        $this->mainRepo = $iScheduleDeleteMessageRepo;
    }
    public function create($request)
    {
        return DbTransactions()->addCallBackJson(function () use ($request) {
            $input = $request->all();

            $input['admin_id'] = auth()->user()->id;
            $input['delay_time'] = $input['time'];

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

            $input = $request->all();
            $input['delay_time'] = $input['time'];

            $record->update($input);

            return $record;
        });
    }
}
