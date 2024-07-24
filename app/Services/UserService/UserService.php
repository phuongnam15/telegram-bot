<?php

namespace App\Services\UserService;

use App\Models\User;
use App\Services\_Abstract\BaseService;
use App\Services\_Trait\SaveFile;

class UserService extends BaseService
{
    use SaveFile;

    public function list() {
        $users = User::where('admin_id', auth()->user()->id)->get();
        return response()->json($users);
    }
}
