<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\PhoneNumber\PhoneNumberService;
use Illuminate\Http\Request;

class PhoneNumberController extends Controller
{
    protected $service;
    public function __construct(PhoneNumberService $phoneNumberService)
    {
        $this->service = $phoneNumberService;
    }

    public function save(Request $request)
    {
        return $this->service->save($request);
    }

    public function get()
    {
        return $this->service->get();
    }
}
