<?php

namespace Rb\Specification\Doctrine\Query;

use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\AbstractSpecification;

/**
 * IndexBy will modify the query-builder so you can specify INDEX BY-statements.
 */
class IndexBy extends AbstractSpecification
{
    /**
     * {@inheritDoc}
     */
    public function modify(QueryBuilder $queryBuilder, $dqlAlias)
    {
        $queryBuilder->indexBy(
            $this->dqlAlias ?: $dqlAlias,
            $this->createPropertyWithAlias($dqlAlias)
        );
    }
}
