<?php

namespace Rb\Specification\Doctrine\Result;

use Doctrine\ORM\AbstractQuery;

/**
 * Interface ModifierInterface.
 */
interface ModifierInterface
{
    /**
     * Modify the query (e.g. select more fields/relations).
     *
     * @param AbstractQuery $query
     */
    public function modify(AbstractQuery $query);
}
