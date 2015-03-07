<?php

namespace Rb\DoctrineSpecification\Result;

use Doctrine\ORM\AbstractQuery;

/**
 * Interface ModifierInterface
 * @package Rb\DoctrineSpecification\Result
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
