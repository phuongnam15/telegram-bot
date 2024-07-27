<?php

namespace App\Services\Command;

use App\Models\BotCommandContent;
use App\Models\Command;
use App\Services\_Abstract\BaseService;
use App\Services\_Exception\AppServiceException;
use GuzzleHttp\Client;

class CommandService extends BaseService
{

    public function list($request)
    {
        return DbTransactions()->addCallBackJson(function () use ($request) {
            $commands = BotCommandContent::where('bot_id', $request->bot_id)->with([
                'command',
                'content' => function ($query) {
                    $query->select('id', 'name');
                }
            ])->get();

            return $commands;
        });
    }
    public function create($input)
    {
        return DbTransactions()->addCallBackJson(function () use ($input) {

            $command = Command::firstOrCreate(
                ['command' => $input['command']],
                [
                    'command' => $input['command']
                ]
            );

            $a = BotCommandContent::firstOrCreate(
                [
                    'bot_id' => $input['bot_id'],
                    'command_id' => $command->id
                ],
                [
                    'bot_id' => $input['bot_id'],
                    'command_id' => $command->id,
                    'content_id' => $input['content_id']
                ]
            );

            return $a;
        });
    }
    public function detail($id)
    {
        return DbTransactions()->addCallBackJson(function () use ($id) {

            $a = BotCommandContent::where('id', $id)->with([
                'bot',
                'command',
                'content'
            ])->first();

            if (!$a) {
                throw new AppServiceException('Not found', 404);
            }

            return $a;
        });
    }
    public function update($id, $input)
    {
        return DbTransactions()->addCallBackJson(function () use ($id, $input) {

            $command = Command::find($id);

            if (!$command) {
                throw new AppServiceException('Command not found', 404);
            }

            $command->update($input);

            return $command;
        });
    }
    public function delete($id)
    {
        return DbTransactions()->addCallBackJson(function () use ($id) {

            $a = BotCommandContent::where('id', $id)->first();

            if (!$a) {
                throw new AppServiceException('Not found', 404);
            }

            $a->delete();

            return $a;
        });
    }
}
