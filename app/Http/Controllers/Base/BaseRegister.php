<?php
namespace App\Http\Controllers\Base;


use App\Http\Requests\Api\RegisterRequest;
use App\Http\Requests\VerifyUserRequest;
use App\Services\_Exception\AppServiceException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

trait BaseRegister {


    /**
     * @param RegisterRequest $request
     * @return mixed
     * @throws AppServiceException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function register(RegisterRequest $request)
    {
       return $this->authService->registerHandle($request);
    }


    /**
     * @param VerifyUserRequest $request
     * @return JsonResponse|null
     * @throws BindingResolutionException
     */
    public function verifyUser(VerifyUserRequest $request): ?JsonResponse
    {
        return $this->authService->activeUserHandle($request->user_id, $request->token);
    }
}
