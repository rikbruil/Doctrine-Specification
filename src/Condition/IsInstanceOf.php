<?php

namespace Rb\Specification\Doctrine\Condition;

use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\AbstractSpecification;
use Rb\Specification\Doctrine\Helper\ValueTrait;

class IsInstanceOf extends AbstractSpecification
{
    use ValueTrait;

    /**
     * @param string $field
     * @param string $className
     * @param string $dqlAlias
     */
    public function __construct($field, $className, $dqlAlias = null)
    {
        $this->setValue($className);

        parent::__construct($field, $dqlAlias);
    }

    /**
     * {@inheritdoc}
     */
    public function modify(QueryBuilder $queryBuilder, $dqlAlias)
    {
        return (string) $queryBuilder->expr()->isInstanceOf(
            $this->createPropertyWithAlias($dqlAlias),
            $this->getValue()
        );
    }
}
