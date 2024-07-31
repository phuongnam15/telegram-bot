<?php

namespace App\Services\UserService;

use App\Models\Bot;
use App\Models\User;
use App\Services\_Abstract\BaseService;
use App\Services\_Exception\AppServiceException;
use App\Services\_Trait\SaveFile;

class UserService extends BaseService
{
    use SaveFile;

    public function list()
    {
        return DbTransactions()->addCallBackJson(function () {
            $bot = Bot::where('id', request()->bot_id)->first();

            if(!$bot){
                throw new AppServiceException('Bot not found');
            }

            $users = $bot->users;

            return $users;
        });
    }
}
