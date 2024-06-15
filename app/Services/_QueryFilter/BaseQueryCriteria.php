<?php

namespace App\Services\_QueryFilter;

use Illuminate\Database\Eloquent\Builder;
use Closure;

abstract class BaseQueryCriteria
{
    protected $value;
    public function __construct($value)
    {
        $this->value = $value;
    }

    public function handle(Builder $builder, Closure $next)
    {
        if ($this->value instanceof Closure) {
            $this->apply($builder);
        } else {
            if (request()->has($this->value)) {
                $this->apply($builder);
            }
        }
        return $next($builder);
    }

    abstract function apply(Builder $builder);

}
