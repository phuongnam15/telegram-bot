<?php

namespace App\Services\AdminService;

use App\Models\AdminModel;
use App\Services\_Abstract\BaseService;
use App\Services\_Exception\AppServiceException;

class AdminService extends BaseService
{
    public function list()
    {
        return DbTransactions()->addCallBackJson(function () {
            $admins = AdminModel::paginate(DEFAULT_PAGINATE);
            return $admins;
        });
    }
    public function updateMoney($request)
    {
        return DbTransactions()->addCallBackJson(function () use ($request) {

            $admin = AdminModel::find($request->id);

            if (!$admin) {
                throw new AppServiceException('not found');
            }

            if ($request->type_update === '+') {
                $admin->money += $request->money;
            } else {
                $admin->money -= $request->money;
            }

            $admin->save();

            return $admin;
        });
    }
    public function me()
    {
        return DbTransactions()->addCallBackJson(function () {
            return auth()->user();
        });
    }
}
