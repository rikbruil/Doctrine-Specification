<?php

namespace Rb\Specification\Doctrine\Condition;

use Doctrine\ORM\Query\Expr\Comparison as DoctrineComparison;
use Doctrine\ORM\QueryBuilder;
use InvalidArgumentException;

class EqualsProperty extends Comparison
{
    /**
     * @param string      $field
     * @param string      $field2
     * @param string|null $dqlAlias
     *
     * @throws InvalidArgumentException
     */
    public function __construct($field, $field2, $dqlAlias)
    {
        parent::__construct(self::EQ, $field, $field2, $dqlAlias);
    }

    /**
     * {@inheritdoc}
     */
    public function modify(QueryBuilder $queryBuilder, $dqlAlias)
    {
        return (string) new DoctrineComparison(
            $this->createPropertyWithAlias($dqlAlias),
            $this->operator,
            $this->createAliasedName($this->value, $dqlAlias)
        );
    }
}
