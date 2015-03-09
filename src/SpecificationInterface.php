<?php

namespace Rb\Specification\Doctrine;

use Doctrine\ORM\QueryBuilder;

/**
 * SpecificationInterface can be used to implement custom specifications.
 */
interface SpecificationInterface extends \Rb\Specification\SpecificationInterface
{
    /**
     * @param QueryBuilder $queryBuilder
     * @param string       $dqlAlias
     *
     * @return string|null
     */
    public function modify(QueryBuilder $queryBuilder, $dqlAlias);
}
