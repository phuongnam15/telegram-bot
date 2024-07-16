<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\CloneService\CloneService;
use Illuminate\Http\Request;

class CloneDataController extends Controller
{
    protected $service;
    public function __construct(CloneService $service)
    {
        $this->service = $service;
    }
    public function clone(Request $request) {
        return $this->service->clone($request);
    }
    public function getData(Request $request) {
        return $this->service->getDataToClone($request);
    }
}
