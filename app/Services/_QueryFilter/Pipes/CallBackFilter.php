<?php

namespace App\Services\_QueryFilter\Pipes;

use App\Services\_QueryFilter\BaseQueryCriteria;
use Illuminate\Database\Eloquent\Builder;
use Closure;

class CallBackFilter extends BaseQueryCriteria
{

    /**
     * @param Builder $builder
     * @return Builder
     */
    function apply(Builder $builder): Builder
    {
        if ($this->value instanceof Closure) {
            return $builder->where($this->value);
        }
        return $builder;
    }
}
