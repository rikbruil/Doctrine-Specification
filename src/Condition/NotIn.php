<?php

namespace Rb\Specification\Doctrine\Condition;

use Doctrine\ORM\QueryBuilder;

class NotIn extends In
{
    public function modify(QueryBuilder $queryBuilder, $dqlAlias)
    {
        $paramName = $this->generateParameterName($queryBuilder);
        $queryBuilder->setParameter($paramName, $this->value);

        return (string) $queryBuilder->expr()->notIn(
            $this->createPropertyWithAlias($dqlAlias),
            sprintf(':%s', $paramName)
        );
    }

    /**
     * @param QueryBuilder $queryBuilder
     *
     * @return string
     */
    private function generateParameterName(QueryBuilder $queryBuilder)
    {
        return sprintf('not_in_%d', count($queryBuilder->getParameters()));
    }
}
