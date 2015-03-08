<?php

namespace Rb\Specification\Doctrine;

use Doctrine\ORM\QueryBuilder;

/**
 * SpecificationInterface can be used to implement custom specifications
 * @package Rb\Specification\Doctrine
 */
interface SpecificationInterface extends \Rb\Specification\SpecificationInterface
{
    /**
     * @param  QueryBuilder $queryBuilder
     * @param  string       $dqlAlias
     * @return string|null
     */
    public function modify(QueryBuilder $queryBuilder, $dqlAlias);
}
