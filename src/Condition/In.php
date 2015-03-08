<?php

namespace Rb\Specification\Doctrine\Condition;

use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\SpecificationInterface;

class In implements SpecificationInterface
{
    /**
     * @var string
     */
    protected $field;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var string|null
     */
    protected $dqlAlias;

    /**
     * @param string      $field
     * @param mixed       $value
     * @param string|null $dqlAlias
     */
    public function __construct($field, $value, $dqlAlias = null)
    {
        $this->field = $field;
        $this->value = $value;
        $this->dqlAlias = $dqlAlias;
    }

    /**
     * {@inheritDoc}
     */
    public function modify(QueryBuilder $queryBuilder, $dqlAlias)
    {
        if ($this->dqlAlias) {
            $dqlAlias = $this->dqlAlias;
        }

        $paramName = $this->generateParameterName($queryBuilder);
        $queryBuilder->setParameter($paramName, $this->value);

        return (string) $queryBuilder->expr()->in(
            sprintf('%s.%s', $dqlAlias, $this->field),
            sprintf(':%s', $paramName)
        );
    }

    /**
     * @param  QueryBuilder $queryBuilder
     * @return string
     */
    public function generateParameterName(QueryBuilder $queryBuilder)
    {
        return sprintf('in_%d', count($queryBuilder->getParameters()));
    }

    /**
     * {@inheritDoc}
     */
    public function isSatisfiedBy($value)
    {
        return true;
    }
}
