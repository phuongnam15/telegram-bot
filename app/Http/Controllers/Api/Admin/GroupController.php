<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\GroupService\GroupService;

class GroupController extends Controller
{
    protected $service;
    public function __construct(GroupService $groupService)
    {
        $this->service = $groupService;
    }

    public function list()
    {
        return $this->service->list();
    }
    public function create(Request $request)
    {
        return $this->service->create($request->all());
    }
    public function detail($id)
    {
        return $this->service->detail($id);
    }
    public function update($id, Request $request)
    {
        return $this->service->update($id, $request->all());
    }
    public function delete($id)
    {
        return $this->service->delete($id);
    }
}
