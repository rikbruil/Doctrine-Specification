<?php

namespace Rb\Specification\Doctrine\Condition;

use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\SpecificationInterface;

class IsNull implements SpecificationInterface
{
    /**
     * @var string
     */
    protected $field;

    /**
     * @var string|null
     */
    protected $dqlAlias;

    /**
     * @param string      $field
     * @param string|null $dqlAlias
     */
    public function __construct($field, $dqlAlias = null)
    {
        $this->field    = $field;
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

        $property = sprintf('%s.%s', $dqlAlias, $this->field);

        return (string) $queryBuilder->expr()->isNull($property);
    }

    /**
     * {@inheritDoc}
     */
    public function isSatisfiedBy($value)
    {
        return true;
    }
}
