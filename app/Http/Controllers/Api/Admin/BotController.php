<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Base\BaseAuth;
use App\Http\Controllers\Controller;
use App\Jobs\SendBotMessage;
use App\Services\BotService\BotService;
use Illuminate\Http\Request;

class BotController extends Controller
{
  use BaseAuth;
  protected $botService;

  public function __construct(BotService $botService)
  {
    $this->botService = $botService;
  }

  public function webhook()
  {
    return $this->botService->webhook();
  }
  public function send(Request $request)
  {
    SendBotMessage::dispatch($request->telegramIds, $request->configId);
    return response()->json(['message' => 'Request received and will be processed.']);
    // return $this->botService->send($request->telegramIds, $request->configId);
  }
}
