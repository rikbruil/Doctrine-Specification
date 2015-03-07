<?php

namespace Rb\Doctrine\Specification\Condition;

use Doctrine\ORM\QueryBuilder;

/**
 * Interface ModifierInterface
 * @package Rb\Doctrine\Specification\Condition
 */
interface ModifierInterface
{
    /**
     * Return a string expression which can be used as condition (in WHERE-clause)
     * @param  QueryBuilder $queryBuilder
     * @param  string       $dqlAlias
     * @return string
     */
    public function getCondition(QueryBuilder $queryBuilder, $dqlAlias);
}
