<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\Command\CommandService;
use Illuminate\Http\Request;
use Telegram\Bot\Objects\Update;

class CommandController extends Controller
{
    private $service;
    function __construct(CommandService $commandService)
    {
        $this->service = $commandService;
    }
    public function store(Request $request) {
        return $this->service->create($request);
    }
    public function list(Request $request) {
        return $this->service->list($request);
    }
    public function show($id) {
        return $this->service->detail($id);
    }
    public function update(Request $request, $id) {
        return $this->service->update($request, $id);
    }
    public function destroy($id) {
        return $this->service->delete($id);
    }
}
