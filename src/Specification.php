<?php

namespace Rb\Doctrine\Specification;

use Doctrine\ORM\QueryBuilder;
use Rb\Doctrine\Specification\Exception\InvalidArgumentException;

/**
 * Specification can be used as a quick-start to writing your own specifications.
 * @package Rb\Doctrine\Specification
 */
class Specification implements SpecificationInterface
{
    /**
     * @var Query\ModifierInterface|Condition\ModifierInterface
     */
    protected $specification;

    /**
     * Set a specification to be used internally
     * @param  Query\ModifierInterface|Condition\ModifierInterface|SpecificationInterface $specification
     * @throws InvalidArgumentException
     */
    public function setSpecification($specification)
    {
        if (! $specification instanceof Condition\ModifierInterface &&
            ! $specification instanceof Query\ModifierInterface) {
            throw new InvalidArgumentException();
        }

        $this->specification = $specification;
    }

    /**
     * {@inheritDoc}
     */
    public function getCondition(QueryBuilder $queryBuilder, $dqlAlias)
    {
        if (! $this->specification instanceof Condition\ModifierInterface) {
            return '';
        }

        return (string) $this->specification
            ->getCondition($queryBuilder, $dqlAlias);
    }

    /**
     * {@inheritDoc}
     */
    public function modify(QueryBuilder $queryBuilder, $dqlAlias)
    {
        if (! $this->specification instanceof Query\ModifierInterface) {
            return;
        }

        $this->specification->modify($queryBuilder, $dqlAlias);
    }

    /**
     * {@inheritDoc}
     */
    public function supports($className)
    {
        if (! $this->specification instanceof SupportInterface) {
            return true;
        }

        return $this->specification->supports($className);
    }
}
