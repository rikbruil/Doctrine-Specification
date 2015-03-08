<?php

namespace Rb\Specification\Doctrine\Condition;

use Doctrine\ORM\QueryBuilder;

/**
 * Interface ModifierInterface
 * @package Rb\Specification\Doctrine\Condition
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
