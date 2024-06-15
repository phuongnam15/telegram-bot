<?php

namespace App\Services\_QueryFilter\Pipes;

use App\Services\_QueryFilter\BaseQueryCriteria;
use Illuminate\Database\Eloquent\Builder;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class WhereLikeFilter extends BaseQueryCriteria
{

    /**
     * @param Builder $builder
     * @return Builder
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    function apply(Builder $builder): Builder
    {
        return $builder->where($this->value, "like", "%". trim(request()->get($this->value)). "%");
    }
}
