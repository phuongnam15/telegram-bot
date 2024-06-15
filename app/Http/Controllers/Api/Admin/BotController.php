<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Base\BaseAuth;
use App\Http\Controllers\Controller;
use App\Services\BotService\BotService;

class BotController extends Controller
{
    use BaseAuth;
    protected $botService;

    public function __construct(BotService $botService) {
      $this->botService = $botService;
    }

    public function webhook() {
        return $this->botService->webhook();
    }
}

