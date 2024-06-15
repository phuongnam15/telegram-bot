<?php

namespace App\Services\_QueryFilter\Pipes;

use App\Services\_QueryFilter\BaseQueryCriteria;
use Illuminate\Database\Eloquent\Builder;
use Closure;
class WhereHasFilter extends BaseQueryCriteria
{
    protected Closure $callBack;
    public function __construct($value, Closure $callBack)
    {
        parent::__construct($value);
        $this->callBack = $callBack;
    }

    function apply(Builder $builder)
    {
        $builder->whereHas($this->value, $this->callBack);
    }
}
