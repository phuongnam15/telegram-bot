<?php
namespace App\Services\_QueryFilter\Pipes;

use App\Services\_QueryFilter\BaseQueryCriteria;
use Illuminate\Database\Eloquent\Builder;

class EqualFilter extends BaseQueryCriteria
{
    function apply(Builder $builder): Builder
    {
        return $builder->where($this->value, request()->input($this->value));
    }
}
