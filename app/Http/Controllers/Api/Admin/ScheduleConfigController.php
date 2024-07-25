<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Services\ScheduleConfigService\ScheduleConfigService;
use App\Http\Controllers\Controller;

class ScheduleConfigController extends Controller
{
    protected $service;
    public function __construct(ScheduleConfigService $scheduleConfigService)
    {
        $this->service = $scheduleConfigService;
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
