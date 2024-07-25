<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ScheduleGroupConfigService\ScheduleGroupConfigService;

class ScheduleGroupConfigController extends Controller
{
    protected $service;
    public function __construct(ScheduleGroupConfigService $scheduleGroupConfigService)
    {
        $this->service = $scheduleGroupConfigService;
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
