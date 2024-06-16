<?php

namespace App\Http\Requests\_Abstracts;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;

abstract class ApiBaseRequest extends BaseRequest
{
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        throw new HttpResponseException(response()->json([
            'msg_error' => $errors->first()
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
//        $errors = (new ValidationException($validator))->errors();
//
//        throw new HttpResponseException(response()->json([
//            'errors' => $errors
//        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
