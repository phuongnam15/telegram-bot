<?php

namespace App\Services\Auth;



use App\Jobs\ResetPasswordEmailJob;
use App\Models\CustomerModel;
use App\Models\FarmerModel;
use App\Services\_Abstract\BaseService;
use App\Services\_Exception\AppServiceException;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PHPUnit\Exception;


abstract class BaseResetPassword extends BaseService
{
    /**
     * @param $user
     * @return mixed
     * @throws AppServiceException
     */
    public function reset($user)
    {
        try {
            DB::beginTransaction();
//            if (!$user->email_verified_at) {
//                throw new AppServiceException("email not verified");
//            }
            $domain = null;
            if ($user->getMorphClass() == CustomerModel::class) {
                $domain = $this->getDomain();
            }

            $user->passwordReset()->delete();
            $token = \Str::random(32);
            $user->passwordReset()->create([
                'email' => $user->email,
                'token' => $token,
            ]);
            $callback = "";
            if (get_class($user) == CustomerModel::class) {
                $callback = $domain->url;
            }

            if (get_class($user) == FarmerModel::class) {
                $callback  = env("FARMER_URL");
            }

            dispatch(new ResetPasswordEmailJob($user, $token, $callback, $domain));
            DB::commit();
            return response()->json([
                "msg" => "Gửi yêu cầu thành công! . Check email để reset password"
            ]);
        }catch (AppServiceException $exception) {
            DB::rollBack();
            Log::info($exception);
            return  $this->sendErrorResponse($exception->getMessage(), null, $exception->getCode());
        }
    }


    /**
     * @param $userId
     * @param $token
     * @return mixed
     * @throws AppServiceException
     */
    public function verifyTokenResetPassword($userId, $token)
    {
        try {
            $user = $this->mainRepos->findWhere(['id' => $userId])->first();
            if (!$user) {
                throw new AppServiceException("invalid url");
            }
            $passwordResetData = $user->passwordReset;
            if ($token != $passwordResetData->token) {
                throw new AppServiceException("invalid url");
            }
            if (Carbon::parse($passwordResetData['created_at'])->addMinutes(config('auth.passwords.users.expire'))->isPast()) {
                throw new AppServiceException("invalid url");
            }
            return $user;
        } catch (AppServiceException $exception) {
            Log::error($exception->getMessage());
            return  null;
        }

    }


    /**
     * @param $userId
     * @param $token
     * @param Request $request
     * @return JsonResponse
     * @throws AppServiceException
     */
    public function changePassword($userId, $token, Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $user =  $this->verifyTokenResetPassword($userId, $token);
            if ($user) {
                $user->update([
                    'password' =>  bcrypt($request->get('password'))
                ]);
            } else {
                throw new AppServiceException("user not found");
            }
            $user->passwordReset()->delete();
            DB::commit();
            return response()->json([
                "msg" => "Thay đổi mật khẩu thành công !"
            ]);
        } catch (AppServiceException|\Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            return  $this->sendErrorResponse($exception->getMessage(), null, $exception->getCode());

        }
    }

}
