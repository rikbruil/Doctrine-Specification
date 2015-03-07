<?php

namespace Rb\DoctrineSpecification\Logic;

use Doctrine\ORM\QueryBuilder;
use Rb\DoctrineSpecification\Condition;
use Rb\DoctrineSpecification\Query;
use Rb\DoctrineSpecification\SpecificationInterface;
use Rb\DoctrineSpecification\SupportInterface;

/**
 * Class Not negates whatever specification/filter is passed inside it
 * @package Rb\DoctrineSpecification\Logic
 */
class Not implements SpecificationInterface
{
    /**
     * @var Condition\ModifierInterface
     */
    private $parent;

    /**
     * @param Condition\ModifierInterface $expr
     */
    public function __construct(Condition\ModifierInterface $expr)
    {
        $this->parent = $expr;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $dqlAlias
     * @return string
     */
    public function getCondition(QueryBuilder $queryBuilder, $dqlAlias)
    {
        $filter = $this->parent->getCondition($queryBuilder, $dqlAlias);
        return (string) $queryBuilder->expr()->not($filter);
    }

    /**
     * Method to modify the given QueryBuilder object
     * @param QueryBuilder $queryBuilder
     * @param string $dqlAlias
     * @return void
     */
    public function modify(QueryBuilder $queryBuilder, $dqlAlias)
    {
        if (! $this->parent instanceof Query\ModifierInterface) {
            return;
        }

        $this->parent->modify($queryBuilder, $dqlAlias);
    }

    /**
     * Check to see if the current specification supports the given class
     * @param string $className
     * @return boolean
     */
    public function supports($className)
    {
        if ($this->parent instanceof SupportInterface) {
            return $this->parent->supports($className);
        }

        return true;
    }
}
