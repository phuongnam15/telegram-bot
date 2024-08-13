<?php

namespace App\Services\UserService;

use App\Models\Bot;
use App\Models\BotUser;
use App\Models\User;
use App\Services\_Abstract\BaseService;
use App\Services\_Exception\AppServiceException;
use App\Services\_Trait\SaveFile;
use Carbon\Carbon;

class UserService extends BaseService
{
    use SaveFile;

    public function list()
    {
        return DbTransactions()->addCallBackJson(function () {
            $bot = Bot::where('id', request()->bot_id)->first();

            if (!$bot) {
                throw new AppServiceException('Bot not found');
            }

            $users = $bot->users;

            return $users;
        });
    }
    public function active()
    {
        return DbTransactions()->addCallBackJson(function () {
            $botUser = BotUser::where('id', request()->id)->first();

            if (!$botUser) {
                throw new AppServiceException('Bot User not found');
            }

            $botUser->is_actived = BotUser::ACTIVE;
            $botUser->expired_at = Carbon::parse($botUser->expired_at)->addMonths(request()->months);
            $botUser->save();

            return $botUser;
        });
    }
    public function update() 
    {
        return DbTransactions()->addCallBackJson(function () {
            $botUser = BotUser::where('id', request()->id)->first();

            if (!$botUser) {
                throw new AppServiceException('Bot User not found');
            }

            $botUser->update(request()->all());

            return $botUser;
        });
    }
}
