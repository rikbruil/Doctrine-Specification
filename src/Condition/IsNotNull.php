<?php

namespace Rb\Doctrine\Specification\Condition;

use Doctrine\ORM\QueryBuilder;

class IsNotNull extends IsNull
{
    /**
     * Return a string expression which can be used as condition (in WHERE-clause)
     * @param QueryBuilder $queryBuilder
     * @param string $dqlAlias
     * @return string
     */
    public function getCondition(QueryBuilder $queryBuilder, $dqlAlias)
    {
        if ($this->dqlAlias) {
            $dqlAlias = $this->dqlAlias;
        }

        $property = sprintf('%s.%s', $dqlAlias, $this->field);
        return (string) $queryBuilder->expr()->isNotNull($property);
    }
}
