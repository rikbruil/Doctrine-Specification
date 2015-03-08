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
        if ($this->dqlAlias) {
            $dqlAlias = $this->dqlAlias;
        }

        $property = sprintf('%s.%s', $dqlAlias, $this->field);

        return (string) $queryBuilder->expr()->isNotNull($property);
    }
}
