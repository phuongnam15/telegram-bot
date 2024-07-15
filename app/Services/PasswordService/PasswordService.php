<?php

namespace App\Services\PasswordService;

use App\Models\Password;
use App\Services\_Abstract\BaseService;

class PasswordService extends BaseService
{

    public function get()
    {
        return DbTransactions()->addCallBackJson(function () {
            
            $passwords = Password::paginate(DEFAULT_PAGINATE);

            return $passwords;
        });
    }
}
