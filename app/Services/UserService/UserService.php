<?php

namespace App\Services\UserService;

use App\Models\User;
use App\Services\_Abstract\BaseService;
use App\Services\_Trait\SaveFile;

class UserService extends BaseService
{
    use SaveFile;

    public function list() {
        $users = User::all();
        return response()->json($users);
    }
}
