<?php

namespace Rb\Specification\Doctrine\Helper;

use Doctrine\ORM\QueryBuilder;

trait ParameterTrait
{
    /**
     * Return automatically generated parameter name.
     *
     * @param QueryBuilder $queryBuilder
     * @param string|null  $name
     *
     * @return string
     */
    protected function generateParameterName(QueryBuilder $queryBuilder, $name = null)
    {
        if (null === $name) {
            $name = get_class($this);
        }

        return sprintf('%s_%d', $name, count($queryBuilder->getParameters()));
    }
}
