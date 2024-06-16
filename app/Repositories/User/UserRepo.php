<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\_Abstract\BaseRepository;
use App\Services\_QueryFilter\Pipes\EqualFilter;

class UserRepo extends BaseRepository
{
    protected $model;

    public function model()
    {
        return User::class;
    }

    public function getFilter()
    {
        return [
            new EqualFilter("status"),
        ];
    }

    public function getUsers()
    {
        $page_size = request()->get('page_size') ? request()->get('page_size') : 10;
        $query = $this->getModel()->newQuery();
        return $this->filterCriteria($query)->paginate($page_size);
    }
}
