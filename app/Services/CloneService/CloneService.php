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
        return DbTransactions()->addCallBackJson(function () use ($request) {

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

            // logger($dataToClone);

            $this->truncateAndInsert($dataTypes, $dataToClone);

            return [
                'message' => 'Cloning initiated successfully!',
            ];
        });
    }
    public function getDataToClone($request)
    {
        try {
            $dataTypes = $request->input('dataType');
            $data = [];

            if (in_array('phone', $dataTypes)) {
                $data['phones'] = PhoneNumber::all()->makeHidden(['created_at', 'updated_at']);
            }
            if (in_array('password', $dataTypes)) {
                $data['passwords'] = Password::all()->makeHidden(['created_at', 'updated_at']);
            }
            if (in_array('content', $dataTypes)) {
                $data['contents'] = ContentConfig::all()->makeHidden(['created_at', 'updated_at']);
            }
            if (in_array('schedule_user', $dataTypes)) {
                $data['scheduleUser'] = ScheduleConfig::all()->makeHidden(['created_at', 'updated_at']);
            }
            if (in_array('schedule_group', $dataTypes)) {
                $data['scheduleGroup'] = ScheduleGroupConfig::all()->makeHidden(['created_at', 'updated_at']);
            }
            if (in_array('schedule_delete', $dataTypes)) {
                $data['scheduleDelete'] = ScheduleDeleteMessage::all()->makeHidden(['created_at', 'updated_at']);
            }
            if (in_array('group', $dataTypes)) {
                $data['groups'] = TelegramGroup::all()->makeHidden(['created_at', 'updated_at']);
            }
            if (in_array('bot', $dataTypes)) {
                $data['bots'] = Bot::all()->makeHidden(['created_at', 'updated_at']);
            }
            if (in_array('user', $dataTypes)) {
                $data['users'] = User::all()->makeHidden(['created_at', 'updated_at']);
            }

            return response()->json($data);
        } catch (\Exception $e) {
            logger($e->getMessage());
            throw new AppServiceException($e->getMessage());
        }
    }
    protected function truncateAndInsert($dataTypes, $dataToClone)
    {
        if (in_array('phone', $dataTypes)) {
            PhoneNumber::truncate();
            PhoneNumber::insert($dataToClone['phones']);
        }

        if (in_array('password', $dataTypes)) {
            Password::truncate();
            Password::insert($dataToClone['passwords']);
        }

        if (in_array('content', $dataTypes)) {
            ContentConfig::truncate();
            ContentConfig::insert($dataToClone['contents']);
        }

        if (in_array('schedule_user', $dataTypes)) {
            ScheduleConfig::truncate();
            ScheduleConfig::insert($dataToClone['scheduleUser']);
        }

        if (in_array('schedule_group', $dataTypes)) {
            ScheduleGroupConfig::truncate();
            ScheduleGroupConfig::insert($dataToClone['scheduleGroup']);
        }

        if (in_array('schedule_delete', $dataTypes)) {
            ScheduleDeleteMessage::truncate();
            ScheduleDeleteMessage::insert($dataToClone['scheduleDelete']);
        }

        if (in_array('group', $dataTypes)) {
            TelegramGroup::truncate();
            TelegramGroup::insert($dataToClone['groups']);
        }

        if (in_array('bot', $dataTypes)) {
            Bot::truncate();
            Bot::insert($dataToClone['bots']);
        }

        if (in_array('user', $dataTypes)) {
            User::truncate();
            User::insert($dataToClone['users']);
        }
    }
}
