<?php

namespace Rb\DoctrineSpecification\Query;

use Doctrine\ORM\QueryBuilder;

interface ModifierInterface
{
    /**
     * Method to modify the given QueryBuilder object
     * @param QueryBuilder $queryBuilder
     * @param string $dqlAlias
     * @return void
     */
    public function modify(QueryBuilder $queryBuilder, $dqlAlias);
}
