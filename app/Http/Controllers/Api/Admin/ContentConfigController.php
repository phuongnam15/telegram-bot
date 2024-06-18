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
    // logger($request->all());
    // logger($request->file('media'));
    // logger($request->buttons);
    // logger(json_decode($request->buttons, true));
    // logger(html_entity_decode($request->content, ENT_QUOTES, 'UTF-8'));
    // logger(sanitizeHtml(html_entity_decode($request->content, ENT_QUOTES, 'UTF-8')));
    // return 1;
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
}
