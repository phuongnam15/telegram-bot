<?php

namespace App\Http\Controllers\Base;

use App\Http\Requests\Api\Admin\UserStoreRequest;
use App\Http\Requests\Api\LoginRequest;
use App\Services\_Exception\AppServiceException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

trait BaseAuth {


    /**
     * @param LoginRequest $request
     * @return mixed
     * @throws AppServiceException
     */
    public function login(Request $request): mixed
    {
        $email = $request->get('email');
        $password = $request->get('password');

        return resolve("app.transactions")->addCallBackWithJsonResponse(function () use ($email, $password){
            $loginData = $this->authService->login($email, $password);
            $loginData['user']['user'] = auth($this->authService->key_auth)->user();
            return response([
                'data' => $loginData['user']
            ])->withHeaders([
                'Authorization' => $loginData['token'],
                'TOKEN-EXPIRE-TIME' => config('jwt.ttl')
            ]);
        });
    }



    public function logout(): Response|JsonResponse|Application|ResponseFactory
    {
        try {
            auth($this->authService->key_auth)->logout();
        } catch (TokenExpiredException $e) {
            return response()->json(['token_expired'], HTTP_UNAUTHORIZED_CODE);
        }

        return response([
            'status' => 'success',
        ]);
    }

    /**
     * @return Response|JsonResponse|Application|ResponseFactory
     */
    public function refresh(): Response|JsonResponse|Application|ResponseFactory
    {
        try {
            $refreshToken = auth($this->authService->key_auth)->refresh(true);
        } catch (\Exception $e) {
            Log::error('Refresh token has expired');
            return response()->json([
                'message' => 'This token can not issue new refresh token'
            ], HTTP_UNAUTHORIZED_CODE);
        }

        return response([])->withHeaders([
            'Authorization' => $refreshToken,
            'TOKEN-EXPIRE-TIME' => config('jwt.ttl') * 60 * 1000
        ]);
    }


    /**
     * @return Response|Application|ResponseFactory
     */
    public function user(): Response|Application|ResponseFactory
    {
        $user = auth()->user();
        return response([
            'data' => $user
        ]);
    }

    public function register(Request $request)
    {
        $input = $request->all();
        if(isset($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        }
        $user = $this->authService->register($input);
        return response([
            'data' => $user
        ]);
    }
}
