<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\PasswordService\PasswordService;

class PasswordController extends Controller
{
    protected $service;
    public function __construct(PasswordService $passwordService)
    {
        $this->service = $passwordService;
    }

    public function get()
    {
        return $this->service->get();
    }
}
