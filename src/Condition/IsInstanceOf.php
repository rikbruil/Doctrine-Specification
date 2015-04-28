<?php

namespace Rb\Specification\Doctrine\Condition;

use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\AbstractSpecification;

class IsInstanceOf extends AbstractSpecification
{
    /**
     * @var string
     */
    private $className;

    /**
     * @param string $field
     * @param string $className
     * @param string $dqlAlias
     */
    public function __construct($field, $className, $dqlAlias = null)
    {
        parent::__construct($field, $dqlAlias);

        $this->className = $className;
    }

    /**
     * {@inheritDoc}
     */
    public function modify(QueryBuilder $queryBuilder, $dqlAlias)
    {
        $expr = $queryBuilder->expr();

        return (string) $expr->isInstanceOf($this->createPropertyWithAlias($dqlAlias), $this->className);
    }
}
