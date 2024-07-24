<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\ContentConfigService\ContentConfigService;
use Illuminate\Http\Request;
use App\Http\Requests\StoreContentConfigRequest;

class ContentConfigController extends Controller
{
  protected $service;

  public function __construct(ContentConfigService $contentConfigService)
  {
    $this->service = $contentConfigService;
  }

  public function store(StoreContentConfigRequest $request)
  {
    return $this->service->create($request);
  }
  public function list(Request $request)
  {
    return $this->service->list($request);
  }
  public function delete($id)
  {
    return $this->service->delete($id);
  }
  public function detail($id)
  {
    return $this->service->detail($id);
  }
  public function update($id, Request $request)
  {
    return $this->service->update($id, $request);
  }
  public function setDefault($id)
  {
    return $this->service->setDefault($id);
  }
}
