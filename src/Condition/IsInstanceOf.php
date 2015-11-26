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
        $this->className = $className;

        parent::__construct($field, $dqlAlias);
    }

    /**
     * {@inheritdoc}
     */
    public function modify(QueryBuilder $queryBuilder, $dqlAlias)
    {
        return (string) $queryBuilder->expr()->isInstanceOf(
            $this->createPropertyWithAlias($dqlAlias),
            $this->className
        );
    }
}
