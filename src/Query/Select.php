<?php

namespace Rb\Specification\Doctrine\Query;

use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\SpecificationInterface;

/**
 * Select will modify the query-builder so you can specify SELECT-statements
 * @package Rb\Specification\Doctrine\Query
 */
class Select implements SpecificationInterface
{
    /**
     * @var string
     */
    protected $select;

    /**
     * @param string|array $select
     */
    public function __construct($select)
    {
        $this->select = $select;
    }

    /**
     * {@inheritDoc}
     */
    public function modify(QueryBuilder $queryBuilder, $dqlAlias)
    {
        $queryBuilder->addSelect($this->select);
    }

    /**
     * {@inheritDoc}
     */
    public function isSatisfiedBy($value)
    {
        return true;
    }
}
