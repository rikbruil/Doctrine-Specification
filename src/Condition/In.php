<?php

namespace Rb\Specification\Doctrine\Condition;

use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\AbstractSpecification;

class In extends AbstractSpecification
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @param string      $field
     * @param mixed       $value
     * @param string|null $dqlAlias
     */
    public function __construct($field, $value, $dqlAlias = null)
    {
        $this->value    = $value;

        parent::__construct($field, $dqlAlias);
    }

    /**
     * {@inheritDoc}
     */
    public function modify(QueryBuilder $queryBuilder, $dqlAlias)
    {
        $paramName = $this->generateParameterName($queryBuilder);
        $queryBuilder->setParameter($paramName, $this->value);

        return (string) $queryBuilder->expr()->in(
            $this->createPropertyWithAlias($dqlAlias),
            sprintf(':%s', $paramName)
        );
    }

    /**
     * @param QueryBuilder $queryBuilder
     *
     * @return string
     */
    protected function generateParameterName(QueryBuilder $queryBuilder)
    {
        return sprintf('in_%d', count($queryBuilder->getParameters()));
    }
}
