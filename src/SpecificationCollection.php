<?php

namespace Rb\Specification\Doctrine;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\Exception\InvalidArgumentException;

class SpecificationCollection extends ArrayCollection implements SpecificationInterface
{
    /**
     * @param SpecificationInterface[] $elements
     */
    public function __construct(array $elements = [])
    {
        array_map([$this, 'add'], $elements);
    }

    /**
     * @param Condition\ModifierInterface|Query\ModifierInterface $value
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function add($value)
    {
        if (! $value instanceof SpecificationInterface) {
            throw new InvalidArgumentException(sprintf(
                '"%s" does not implement "%s"!',
                (is_object($value)) ? get_class($value) : $value,
                SpecificationInterface::class
            ));
        }

        return parent::add($value);
    }

    /**
     * {@inheritDoc}
     */
    public function modify(QueryBuilder $queryBuilder, $dqlAlias)
    {
        $match = function (SpecificationInterface $specification) use ($queryBuilder, $dqlAlias) {
            return $specification->modify($queryBuilder, $dqlAlias);
        };

        $result = array_filter(array_map($match, $this->toArray()));
        if (empty($result)) {
            return;
        }

        return call_user_func_array(
            [$queryBuilder->expr(), 'andX'],
            $result
        );
    }

    /**
     * {@inheritDoc}
     */
    public function isSatisfiedBy($value)
    {
        /** @var SpecificationInterface $child */
        foreach ($this as $child) {
            if (! $child->isSatisfiedBy($value)) {
                return false;
            }
        }

        return true;
    }
}
