<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendBotMessage;
use App\Services\BotService\BotService;
use Illuminate\Http\Request;

class BotController extends Controller
{
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
    SendBotMessage::dispatch($request->telegramIds, $request->configId)->onQueue('botSendMessage');
    return response()->json(['message' => 'Đã nhận yêu cầu, chờ gửi ...']);
  }
  public function saveBot(Request $request) {
    return $this->botService->saveBot($request);
  }
  public function list(){
    return $this->botService->list();
  }
  public function activeBot($id){
    return $this->botService->activeBot($id);
  }
  public function delete($id){
    return $this->botService->delete($id);
  }
}
