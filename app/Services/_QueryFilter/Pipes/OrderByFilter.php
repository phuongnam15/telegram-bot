<?php

namespace App\Services\_QueryFilter\Pipes;

use App\Services\_QueryFilter\BaseQueryCriteria;
use Illuminate\Database\Eloquent\Builder;

class OrderByFilter extends BaseQueryCriteria
{
    protected string $direction;
    public function __construct($value, $direction = "ASC")
    {
        parent::__construct($value);
        $this->direction = $direction;
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    function apply(Builder $builder): Builder
    {
        return $builder->orderBy($this->value, $this->direction);
    }
}
