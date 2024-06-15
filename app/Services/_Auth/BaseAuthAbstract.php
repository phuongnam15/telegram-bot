<?php

namespace App\Services\_Auth;

use App\Services\_Abstract\BaseService;
use App\Services\_Exception\AppServiceException;

abstract class BaseAuthAbstract extends BaseService
{

    /**
     * @param $email
     * @param $password
     * @return array
     * @throws AppServiceException
     */
    public function login($email, $password): array
    {
        if (!$token = auth($this->key_auth)->attempt([
            'email' => $email,
            'password' => $password,
        ])) {
            throw new AppServiceException("Email hoặc mật khẩu không chính xác !");
        }
        $userData['token'] = $token;
        $userData['expired'] = config('jwt.ttl');
        if($this->agent && $this->userHistoryRepo) {
            $data = [
                'user_agent' => request()->header('User-Agent'),
                'ip' => request()->ip(),
                'user_id' => auth($this->key_auth)->user()->id,
            ];
            $this->userHistoryRepo->create($data);
        }
        return [
            'user' => $userData,
            'token' => $token,
        ];
    }

    /**
     * @param $input
     * @return void
     * @throws AppServiceException
     */
    public function register($input)
    {
        if ($this->mainRepository->findWhere(['email' => $input['email'], "deleted_at" => null])->first()) {
            throw new AppServiceException("Địa chỉ email đã tồn tại !");
        }
        return resolve("app.transactions")->addCallBackWithJsonResponse(function () use ($input){
            $user = $this->mainRepository->create($input);
        });
    }

}
