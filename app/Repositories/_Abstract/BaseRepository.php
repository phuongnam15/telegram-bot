<?php

namespace App\Repositories\_Abstract;

use App\Services\_QueryFilter\TraitFilterCriteria;
use Prettus\Repository\Eloquent\BaseRepository as BRepository;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class BaseRepository
 *
 * @package App\Entities\Admin\Repositories
 */
abstract class BaseRepository extends BRepository implements BaseRepositoryInterface
{
    use TraitFilterCriteria;

    protected $filters = [];
    public function getSelect()
    {
        return $this->select('id', 'name')->orderBy('id', 'desc')->get();
    }


    /**
     * @return Builder
     */
    public function getQuery(): Builder
    {
        return $this->getModel()->newQuery();
    }

    public function findById($id)
    {
        return $this->findWhere(['id' => $id])->first();
    }

    public function setFilters(array $filters)
    {
        $this->filters = $filters;
    }
}
