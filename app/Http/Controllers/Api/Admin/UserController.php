<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Base\BaseAuth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserService\UserService;

class UserController extends Controller
{
  use BaseAuth;
  protected $service;

  public function __construct(UserService $userService)
  {
    $this->service = $userService;
  }

  public function list()
  {
    return $this->service->list();
  }
}
