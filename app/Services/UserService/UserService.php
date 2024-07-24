<?php

namespace App\Services\UserService;

use App\Models\User;
use App\Services\_Abstract\BaseService;
use App\Services\_Trait\SaveFile;

class UserService extends BaseService
{
    use SaveFile;

    public function list()
    {
        $admin = auth()->user();
        $users = $admin->users;
        return response()->json($users);
    }
}
