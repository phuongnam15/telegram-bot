<?php

namespace App\Services\_QueryFilter;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;

trait TraitFilterCriteria
{
    /**
     * @param Builder $builder
     * @return mixed
     */
    public function filterCriteria(Builder $builder)
    {
        $criteria = $this->getFilterCriteria();
        return resolve(Pipeline::class)
            ->send($builder)->through($criteria)
            ->thenReturn();
    }

    /**
     * @return array
     */
    public function getFilterCriteria(): array
    {
        return $this->filters;
    }

    public function filterWithPagination(Builder $builder)
    {
        $query = $this->filterCriteria($builder);
        $perPage = (request()->has("perPage") &&
            request()->get("perPage") &&
            is_numeric(request()->get("perPage"))) ? request()->get("perPage") : DEFAULT_PAGINATE;

        return $query->paginate($perPage);
    }
}
