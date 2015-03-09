<?php

namespace Rb\Specification\Doctrine\Logic;

use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\SpecificationInterface;

/**
 * Class Not negates whatever specification/filter is passed inside it.
 */
class Not implements SpecificationInterface
{
    /**
     * @var SpecificationInterface
     */
    private $parent;

    /**
     * @param SpecificationInterface $expr
     */
    public function __construct(SpecificationInterface $expr)
    {
        $this->parent = $expr;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string       $dqlAlias
     *
     * @return string
     */
    public function modify(QueryBuilder $queryBuilder, $dqlAlias)
    {
        $filter = $this->parent->modify($queryBuilder, $dqlAlias);
        if (empty($filter)) {
            return '';
        }

        return (string) $queryBuilder->expr()->not($filter);
    }

    /**
     * {@inheritDoc}
     */
    public function isSatisfiedBy($value)
    {
        return $this->parent->isSatisfiedBy($value);
    }
}
