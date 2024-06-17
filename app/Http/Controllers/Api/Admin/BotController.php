<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Base\BaseAuth;
use App\Http\Controllers\Controller;
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
    logger(123123);
    return $this->botService->webhook();
  }
  public function send(Request $request)
  {
    return $this->botService->send($request->telegram_id, $request->config_id);
  }
}
