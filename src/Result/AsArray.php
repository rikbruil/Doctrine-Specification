<?php

namespace Rb\Specification\Doctrine\Result;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;

/**
 * Hydrate results as array instead of objects.
 */
class AsArray implements ModifierInterface
{
    /**
     * Modify the query (e.g. select more fields/relations).
     *
     * @param AbstractQuery $query
     */
    public function modify(AbstractQuery $query)
    {
        $query->setHydrationMode(Query::HYDRATE_ARRAY);
    }
}
