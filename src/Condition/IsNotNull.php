<?php

namespace Rb\Specification\Doctrine\Condition;

use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\AbstractSpecification;

class IsNotNull extends AbstractSpecification
{
    /**
     * {@inheritdoc}
     */
    public function modify(QueryBuilder $queryBuilder, $dqlAlias)
    {
        return (string) $queryBuilder->expr()->isNotNull(
            $this->createPropertyWithAlias($dqlAlias)
        );
    }
}
