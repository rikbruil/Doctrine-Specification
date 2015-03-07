<?php

namespace Rb\Doctrine\Specification\Result;

use Doctrine\ORM\AbstractQuery;

/**
 * Interface ModifierInterface
 * @package Rb\Doctrine\Specification\Result
 */
interface ModifierInterface
{
    /**
     * Modify the query (e.g. select more fields/relations)
     * @param AbstractQuery $query
     * @return void
     */
    public function modify(AbstractQuery $query);
}
