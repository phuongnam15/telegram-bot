<?php

namespace App\Services\CloneService;

use App\Models\Bot;
use App\Models\ContentConfig;
use App\Models\Password;
use App\Models\PhoneNumber;
use App\Models\ScheduleConfig;
use App\Models\ScheduleDeleteMessage;
use App\Models\ScheduleGroupConfig;
use App\Models\TelegramGroup;
use App\Models\User;
use App\Services\_Abstract\BaseService;
use App\Services\_Exception\AppServiceException;
use Illuminate\Support\Facades\Http;

class CloneService extends BaseService
{
    public function clone($request)
    {
        try {
            $domain = $request->domain;
            $dataTypes = $request->dataTypeToClone;

            $apiUrl = rtrim($domain, '/') . '/api/admin/clone';

            $response = Http::timeout(60)->get($apiUrl, [
                'dataType' => $dataTypes,
            ]);

            if ($response->failed()) {
                return [
                    'message' => 'Failed to get data for cloning.',
                ];
            }

            $dataToClone = $response->json();

            $this->truncateAndInsert($dataTypes, $dataToClone);

            return [
                'message' => 'Cloning initiated successfully!',
            ];
        } catch (\Exception $e) {
            logger($e->getMessage());
            return response()->json([ 'message' => $e->getMessage() ]);
        }
    }
    public function getDataToClone($request)
    {
        try {
            $dataTypes = $request->input('dataType');
            $data = [];

            // if (in_array('phone', $dataTypes)) {
            //     $data['phones'] = PhoneNumber::all()->makeHidden(['created_at', 'updated_at']);
            // }
            // if (in_array('password', $dataTypes)) {
            //     $data['passwords'] = Password::all()->makeHidden(['created_at', 'updated_at']);
            // }
            if (in_array('content', $dataTypes)) {
                $data['contents'] = ContentConfig::all()->makeHidden(['created_at', 'updated_at']);
            }
            // if (in_array('schedule_user', $dataTypes)) {
            //     $data['scheduleUser'] = ScheduleConfig::all()->makeHidden(['created_at', 'updated_at']);
            // }
            // if (in_array('schedule_group', $dataTypes)) {
            //     $data['scheduleGroup'] = ScheduleGroupConfig::all()->makeHidden(['created_at', 'updated_at']);
            // }
            // if (in_array('schedule_delete', $dataTypes)) {
            //     $data['scheduleDelete'] = ScheduleDeleteMessage::all()->makeHidden(['created_at', 'updated_at']);
            // }
            // if (in_array('group', $dataTypes)) {
            //     $data['groups'] = TelegramGroup::all()->makeHidden(['created_at', 'updated_at']);
            // }
            // if (in_array('bot', $dataTypes)) {
            //     $data['bots'] = Bot::all()->makeHidden(['created_at', 'updated_at']);
            // }
            // if (in_array('user', $dataTypes)) {
            //     $data['users'] = User::all()->makeHidden(['created_at', 'updated_at']);
            // }

            return response()->json($data);
        } catch (\Exception $e) {
            logger($e->getMessage());
            return response()->json([
                'message' => $e->getMessage(),
            ]);
        }
    }
    protected function truncateAndInsert($dataTypes, $dataToClone)
    {
        $adminId = auth()->user()->id;
        // if (in_array('phone', $dataTypes)) {
        //     PhoneNumber::truncate();
        //     foreach($dataToClone['phones'] as $phone) {
        //         PhoneNumber::create($phone);
        //     }
        // }

        // if (in_array('password', $dataTypes)) {
        //     Password::truncate();
        //     foreach($dataToClone['passwords'] as $password) {
        //         Password::create($password);
        //     }
        // }

        if (in_array('content', $dataTypes)) {
            ContentConfig::truncate();
            foreach($dataToClone['contents'] as $content) {
                $content['admin_id'] = $adminId;
                ContentConfig::create($content);
            }
        }

        // if (in_array('schedule_user', $dataTypes)) {
        //     ScheduleConfig::truncate();
        //     foreach($dataToClone['scheduleUser'] as $scheduleUser) {
        //         ScheduleConfig::create($scheduleUser);
        //     }
        // }

        // if (in_array('schedule_group', $dataTypes)) {
        //     ScheduleGroupConfig::truncate();
        //     foreach($dataToClone['scheduleGroup'] as $scheduleGroup) {
        //         ScheduleGroupConfig::create($scheduleGroup);
        //     }
        // }

        // if (in_array('schedule_delete', $dataTypes)) {
        //     ScheduleDeleteMessage::truncate();
        //     foreach($dataToClone['scheduleDelete'] as $scheduleDelete) {
        //         ScheduleDeleteMessage::create($scheduleDelete);
        //     }
        // }

        // if (in_array('group', $dataTypes)) {
        //     TelegramGroup::truncate();
        //     foreach($dataToClone['groups'] as $group) {
        //         TelegramGroup::create($group);
        //     }
        // }

        // if (in_array('bot', $dataTypes)) {
        //     Bot::truncate();
        //     foreach($dataToClone['bots'] as $bot) {
        //         Bot::create($bot);
        //     }
        // }

        // if (in_array('user', $dataTypes)) {
        //     User::truncate();
        //     foreach($dataToClone['users'] as $user) {
        //         User::create($user);
        //     }
        // }
    }
}
