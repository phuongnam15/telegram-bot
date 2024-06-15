<?php

namespace App\Services\_Auth;

use App\Repositories\Admin\AdminRepo;

class AuthAdminService extends BaseAuthAbstract
{
    public $mainRepository;

    public $key_auth = KEY_AUTH_ADMIN;
    public $agent;
    public $userHistoryRepo;

    public function __construct(AdminRepo $mainRepository)
    {
       $this->mainRepository = $mainRepository;
    }

    public function getProfile() {
        $admin = auth(KEY_AUTH_ADMIN)->user();
        return $this->sendSuccessResponse($admin);
    }
}