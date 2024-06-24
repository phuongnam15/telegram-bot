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

    public function getSchedule() {
        return $this->service->get();
    }
    public function configShedule(Request $request) {
        return $this->service->update($request);
    }
}
