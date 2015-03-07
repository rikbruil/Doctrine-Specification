<?php

namespace Rb\Doctrine\Specification\Query;

use Doctrine\ORM\QueryBuilder;

/**
 * Select will modify the query-builder so you can specify SELECT-statements
 * @package Rb\Doctrine\Specification\Query
 */
class Select implements ModifierInterface
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
     * Method to modify the given QueryBuilder object
     * @param  QueryBuilder $queryBuilder
     * @param  string       $dqlAlias
     * @return void
     */
    public function modify(QueryBuilder $queryBuilder, $dqlAlias)
    {
        $queryBuilder->addSelect($this->select);
    }
}
