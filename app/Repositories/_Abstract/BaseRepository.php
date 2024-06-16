<?php

namespace App\Repositories\_Abstract;

use App\Services\_QueryFilter\TraitFilterCriteria;
use Prettus\Repository\Eloquent\BaseRepository as BRepository;

/**
 * Class BaseRepository
 *
 * @package App\Entities\Admin\Repositories
 */
abstract class BaseRepository extends BRepository implements BaseRepositoryInterface
{
    use TraitFilterCriteria;

    function getQuery()
    {
      return  $this->getModel()->newQuery();
    }
}
