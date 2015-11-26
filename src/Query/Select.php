<?php

namespace Rb\Specification\Doctrine\Query;

use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\SpecificationInterface;

/**
 * Select will modify the query-builder so you can specify SELECT-statements.
 */
class Select implements SpecificationInterface
{
    /**
     * @var string|array
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
     * {@inheritdoc}
     */
    public function modify(QueryBuilder $queryBuilder, $dqlAlias)
    {
        $queryBuilder->addSelect($this->select);
    }

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($value)
    {
        return true;
    }
}
