<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\ScheduleDeleteMessageService\ScheduleDeleteMessageService;
use Illuminate\Http\Request;

class ScheduleDeleteMessageController extends Controller
{
    protected $service;
    public function __construct(ScheduleDeleteMessageService $scheduleDeleteMessageService){
        $this->service = $scheduleDeleteMessageService;
    }

    public function store(Request $request)
    {
        return $this->service->create($request);
    }
    public function update(Request $request, $id)
    {
        return $this->service->update($request, $id);
    }
}
