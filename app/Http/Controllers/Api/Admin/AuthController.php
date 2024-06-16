<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Base\BaseAuth;
use App\Http\Controllers\Controller;
use App\Services\_Auth\AuthAdminService;

class AuthController extends Controller
{
    use BaseAuth;
    protected $authService;

    public function __construct(AuthAdminService $authService) {
        $this->authService = $authService;
    }

    public function getProfile() {
        return $this->authService->getProfile();
    }
}
