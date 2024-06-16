<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Base\BaseAuth;
use App\Http\Controllers\Controller;
use App\Services\ContentConfigService\ContentConfigService;
use Illuminate\Http\Request;
use App\Http\Requests\StoreContentConfigRequest;

class ContentConfigController extends Controller
{
  use BaseAuth;
  protected $service;

  public function __construct(ContentConfigService $contentConfigService)
  {
    $this->service = $contentConfigService;
  }

  public function store(StoreContentConfigRequest $request)
  {
    return $this->service->create($request);
  }
}
