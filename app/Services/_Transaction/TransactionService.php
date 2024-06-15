<?php
namespace App\Services\_Transaction;

use App\Services\_Exception\AppServiceException;
use App\Services\_Response\ApiResponseProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PHPUnit\Exception;

class TransactionService extends ApiResponseProvider
{
    /**
     * @param $callback
     * @return void
     * @throws AppServiceException
     */
    public function addCallback($callback)
    {
        try {
            DB::beginTransaction();
            $result = $callback();
            DB::commit();
            return $result;
        }catch (AppServiceException|Exception $exception) {
            DB::rollBack();
            Log::info($exception->getMessage());
            return responseError($exception->getMessage());
        }
    }


    /**
     * @param \Closure $callback
     * @return JsonResponse
     */
    public function addCallBackWithJsonResponse(\Closure $callback, $msg = "success"): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = $callback();
            DB::commit();
            return $this->sendSuccessResponse($data, $msg);
        }catch (AppServiceException|Exception $exception) {
            DB::rollBack();
            Log::info($exception->getMessage());
            return $this->sendErrorResponse($exception->getMessage());
        }
    }

    public function addCallbackJson($callback)
    {
        try {
            DB::beginTransaction();
            $result = $callback();
            DB::commit();
            return $result;
        }catch (AppServiceException|Exception $exception) {
            DB::rollBack();
            Log::info($exception->getMessage());
            return  $this->sendErrorResponse($exception->getMessage());
        }
    }
}
