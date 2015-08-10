<?php

namespace Rb\Specification\Doctrine\Condition;

use Doctrine\ORM\QueryBuilder;

class IsNotNull extends IsNull
{
    /**
     * {@inheritDoc}
     */
    public function modify(QueryBuilder $queryBuilder, $dqlAlias)
    {
        return (string) $queryBuilder->expr()->isNotNull(
            $this->createPropertyWithAlias($dqlAlias)
        );
    }
}
