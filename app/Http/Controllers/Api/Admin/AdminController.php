<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminService\AdminService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $service;
    function __construct(AdminService $adminService)
    {
        $this->service = $adminService;
    }
    public function me()
    {
        return $this->service->me();
    }
}
