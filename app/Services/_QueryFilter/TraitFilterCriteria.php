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
    public function filterCriteria(Builder $builder): mixed
    {
        $criteria = $this->getFilterCriteria();
        return app(Pipeline::class)
            ->send($builder)
            ->through($criteria)
            ->thenReturn();
    }

    /**
     * @return array
     */
    public function getFilterCriteria(): array
    {
        if (method_exists($this, "getFilter")) {
            return $this->getFilter();
        }

        return [];
    }

    public function filterWithPagination()
    {
        $page_size = request()->get('page_size') ? request()->get('page_size') : 10;
        return $this->filterCriteria($this->getQuery())->orderBy('updated_at', 'DESC')->paginate($page_size);
    }
}
