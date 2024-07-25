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

  public function webhook(Request $request, $botId)
  {
    return $this->botService->webhook($request, $botId);
  }
  public function send(Request $request)
  {
    SendBotMessage::dispatch($request->telegramIds, $request->configId, $request->botToken)->onQueue('botSendMessage');
    return response()->json(['message' => 'Đã nhận yêu cầu, chờ gửi ...']);
  }
  public function saveBot(Request $request) {
    return $this->botService->saveBot($request);
  }
  public function list(){
    return $this->botService->list();
  }
  public function activeBot($id, Request $request){
    return $this->botService->activeBot($id, $request);
  }
  public function delete($id){
    return $this->botService->delete($id);
  }
  public function detail($id) {
    return $this->botService->listScheduleBot($id);
  }
}
