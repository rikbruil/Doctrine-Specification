<?php

namespace Rb\Specification\Doctrine;

use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\Exception\InvalidArgumentException;

/**
 * Specification can be used as a quick-start to writing your own specifications.
 */
class Specification implements SpecificationInterface
{
    /**
     * @var SpecificationInterface
     */
    protected $specification;

    /**
     * Set a specification to be used internally.
     *
     * @param SpecificationInterface $specification
     *
     * @throws InvalidArgumentException
     */
    public function setSpecification($specification)
    {
        if (! $specification instanceof SpecificationInterface) {
            throw new InvalidArgumentException();
        }

        $this->specification = $specification;
    }

    /**
     * {@inheritDoc}
     */
    public function modify(QueryBuilder $queryBuilder, $dqlAlias)
    {
        return (string) $this->specification
            ->modify($queryBuilder, $dqlAlias);
    }

    /**
     * {@inheritDoc}
     */
    public function isSatisfiedBy($value)
    {
        return $this->specification->isSatisfiedBy($value);
    }
}
