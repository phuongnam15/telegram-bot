<?php

namespace App\Services\PhoneNumber;

use App\Models\PhoneNumber;
use App\Services\_Abstract\BaseService;
use App\Services\_Exception\AppServiceException;

class PhoneNumberService extends BaseService
{

    public function __construct()
    {
    }

    public function save($request)
    {
        return DbTransactions()->addCallBackJson(function () use ($request) {
            $phoneNumbers = $request->phones;
            $validPhoneNumbers = [];
            $invalidPhoneNumbers = [];

            foreach ($phoneNumbers as $phone) {
                if (preg_match('/^\d+$/', $phone)) {
                    if (!PhoneNumber::where('phone_number', $phone)->exists()) {
                        $validPhoneNumbers[] = $phone;
                    }
                } else {
                    $invalidPhoneNumbers[] = $phone;
                }
            }

            foreach ($validPhoneNumbers as $phone) {
                PhoneNumber::create(['phone_number' => $phone]);
            }

            return [
                'message' => 'Success',
                'invalid_phones' => $invalidPhoneNumbers,
            ];
        });
    }


    public function get()
    {
        return DbTransactions()->addCallBackJson(function () {
            
            $phoneNumbers = PhoneNumber::paginate(DEFAULT_PAGINATE);

            return $phoneNumbers;
        });
    }
}
