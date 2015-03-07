<?php

namespace Rb\Doctrine\Specification\Result;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;

/**
 * Hydrate results as array instead of objects
 * @package Rb\Doctrine\Specification\Result
 */
class AsArray implements ModifierInterface
{
    /**
     * Modify the query (e.g. select more fields/relations)
     * @param  AbstractQuery $query
     * @return void
     */
    public function modify(AbstractQuery $query)
    {
        $query->setHydrationMode(Query::HYDRATE_ARRAY);
    }
}
