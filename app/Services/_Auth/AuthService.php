<?php

namespace App\Services\_Auth;

use App\Repositories\User\UserRepo;
use App\Repositories\UserHistory\UserHistoryRepo;
use App\Repositories\PasswordReset\PasswordResetRepo;
use App\Services\_Exception\AppServiceException;
use App\Services\UserRefChildService\UserRefChildService;
use App\Mail\ForgotPassword;
use App\Models\PasswordResetModel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailable;
use Jenssegers\Agent\Agent;
use Ramsey\Uuid\Uuid;

class AuthService extends BaseAuthAbstract
{
    public $mainRepository;
    public $passResetRepository;
    public $userHistoryRepo;
    public $userRefChildService;
    public $key_auth = KEY_AUTH_USER;
    public $agent;
    public function __construct(
        PasswordResetRepo $passResetRepository,
        UserRepo $mainRepository,
        UserRefChildService $userRefChildService,
        Agent $agent,
        UserHistoryRepo $userHistoryRepo
    ) {
        $this->mainRepository = $mainRepository;
        $this->passResetRepository = $passResetRepository;
        $this->userRefChildService = $userRefChildService;
        $this->agent = $agent;
        $this->userHistoryRepo = $userHistoryRepo;
    }

    public function register($input)
    {
        if ($this->mainRepository->findWhere(['email' => $input['email'], "deleted_at" => null])->first()) {
            throw new AppServiceException("Địa chỉ email đã tồn tại !");
        }
        $input['code_ref'] = uniqid();
        return resolve("app.transactions")->addCallBackWithJsonResponse(function () use ($input) {
            $user = $this->mainRepository->create($input);
            if (isset($input['ref'])) {
                $this->userRefChildService->createParents($input['ref'], $user);
            }
            return $user;
        });
    }

    public function changePassword($input)
    {
        $user = auth()->user();
        $password = Hash::make($input['password']);
        if ($user->update(['password'=> $password])) {
            return $this->sendSuccessResponse($user,'Đổi mật khẩu thành công');
        }else{
            return $this->sendErrorResponse('Đổi mật khẩu thành công');
        }

    }

    public function forgotPassword($input)
    {
        $token =  Uuid::uuid4()->toString();;
        if (!$this->mainRepository->findWhere(['email' => $input['email'], "deleted_at" => null])->first()) {
            throw new AppServiceException("Địa chỉ email không tồn tại !");
        }
        $user = $this->mainRepository->findWhere(['email' => $input['email'], "deleted_at" => null])->first();
        $this->passResetRepository->create(['email'=>$input['email'], 'token' => $token]);
        Mail::to($input['email'])->send(new ForgotPassword($user, $token));
        return $this->sendSuccessResponse([]);
    }

    public function resetPassword($input)
    {
        $passReset = $this->passResetRepository->findWhere(['email' => $input['email'], 'token' => $input['token'], "deleted_at" => null])->first();
        if(!$passReset)
        {
            return $this->sendErrorResponse('Không thành công!');
        }
        $user = $this->mainRepository->findWhere(['email' => $input['email'], "deleted_at" => null])->first();
        if(!$user)
        {
            return $this->sendErrorResponse('Email không tồn tại!');
        }
        $password = Hash::make($input['password']);
        $user->update(['password'=> $password]);
        if ($user->update(['password'=> $password])) {
            PasswordResetModel::where('email', $input['email'])->where('token', $input['token'])->update(['deleted_at' => date('Y-m-d H:i:s')]);
            return $this->sendSuccessResponse($user,'Đổi mật khẩu thành công');
        }else{
            return $this->sendErrorResponse('Đổi mật khẩu không thành công');
        }

    }
}
