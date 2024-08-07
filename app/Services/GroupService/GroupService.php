<?php

namespace App\Services\GroupService;

use App\Models\Bot;
use App\Models\TelegramGroup;
use App\Services\_Abstract\BaseService;
use App\Services\_Exception\AppServiceException;
use App\Services\_Trait\SaveFile;
use Carbon\Carbon;
use GuzzleHttp\Client;

class GroupService extends BaseService
{
    use SaveFile;

    public function list()
    {
        return DbTransactions()->addCallBackJson(function () {
            $bot = Bot::where('id', request()->bot_id)->first();
            $groups = $bot->groups;
            return $groups;
        });
    }
    public function create($input)
    {
        return DbTransactions()->addCallBackJson(function () use ($input) {

            $client = new Client();

            $botToken = $input['bot_token'];
            $chatId = $input['telegram_id'];

            $response = $client->get("https://api.telegram.org/bot{$botToken}/getChat", [
                'query' => ['chat_id' => $chatId]
            ]);

            $data = json_decode($response->getBody(), true);

            if (!$data['ok']) {
                throw new AppServiceException('Group not found');
            }

            $chat = $data['result'];
            $input['name'] = $chat['title'] ?? $chat['username'];
            $input['admin_id'] = auth()->user()->id;

            $a = TelegramGroup::create($input);

            return $a;
        });
    }
    public function detail($id)
    {
        $a = TelegramGroup::find($id);

        if (!$a) {
            return response()->json([
                'message' => 'Group not found'
            ]);
        }

        return response()->json($a);
    }
    public function update($id, $input)
    {

        $a = TelegramGroup::find($id);

        if (!$a) {
            return response()->json([
                'message' => 'Group not found'
            ]);
        }

        $a->update($input);

        return response()->json($a);
    }
    public function delete($id)
    {
        $a = TelegramGroup::find($id);

        if (!$a) {
            return response()->json([
                'message' => 'Group not found'
            ]);
        }

        $a->delete();

        return response()->json([
            'message' => 'Group deleted'
        ]);
    }
    public function analyticMessage($request)
    {
        return DbTransactions()->addCallBackJson(function () use ($request) {
            $group = TelegramGroup::where('id', request()->group_id)->first();

            if (!$group) {
                throw new AppServiceException('Group not found');
            }

            $analytic = $group->analyticMessages->whereBetween('created_at', [
                Carbon::parse($request->start_at),
                Carbon::parse($request->end_at)
            ]);

            return $analytic;
        });
    }
    public function analyticUser($request)
    {
        return DbTransactions()->addCallBackJson(function () use ($request) {
            $group = TelegramGroup::where('id', $request->group_id)->first();

            if (!$group) {
                throw new AppServiceException('Group not found');
            }

            $analytic = $group->analyticUsers->where('type', $request->type)->whereBetween('created_at', [
                Carbon::parse($request->start_at),
                Carbon::parse($request->end_at)
            ]);

            return $analytic;
        });
    }
}
