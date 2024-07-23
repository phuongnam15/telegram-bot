<?php

namespace App\Services\Auth;

use App\Models\AdminModel;
use App\Services\_Abstract\BaseService;
use App\Services\_Exception\AppServiceException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService extends BaseService
{
    public function login($request)
    {
        return DbTransactions()->addCallbackJson(function () use ($request) {

            $credentials = $request->all();
            if (!$token = auth()->attempt($credentials)) {
                throw new AppServiceException("Tài khoản không tồn tại.");
            }

            $user = Auth::user();

            return [
                'user' => $user,
                'token' => $token
            ];
        });
    }

    public function register($request)
    {
        return DbTransactions()->addCallbackJson(function () use ($request) {

            $data = $request->all();

            $data['password'] = Hash::make($data['password']);

            AdminModel::create($data);

            return "Đăng ký thành công";
        });
    }

}
