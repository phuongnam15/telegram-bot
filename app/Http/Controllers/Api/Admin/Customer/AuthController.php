<?php

namespace App\Http\Controllers\Api\Admin\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }
    public function login(Request $request){
        return $this->authService->login($request);
    }
    public function register(RegisterRequest $request){
        return $this->authService->register($request);
    }
   
}
