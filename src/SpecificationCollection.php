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
     * Method to modify the given QueryBuilder object
     * @param  QueryBuilder $queryBuilder
     * @param  string       $dqlAlias
     * @return void
     */
    public function modify(QueryBuilder $queryBuilder, $dqlAlias)
    {
        foreach ($this as $child) {
            if (! $child instanceof Query\ModifierInterface) {
                continue;
            }

            $child->modify($queryBuilder, $dqlAlias);
        }
    }

    /**
     * @param  Condition\ModifierInterface|Query\ModifierInterface $value
     * @return bool
     * @throws InvalidArgumentException
     */
    public function add($value)
    {
        if (! $value instanceof Condition\ModifierInterface &&
            ! $value instanceof Query\ModifierInterface) {
            throw new InvalidArgumentException(sprintf(
                '"%s" does not implement any ModifierInterface!',
                (is_object($value)) ? get_class($value) : $value
            ));
        }

        return parent::add($value);
    }

    /**
     * Return a string expression which can be used as condition (in WHERE-clause)
     * @param  QueryBuilder $queryBuilder
     * @param  string       $dqlAlias
     * @return string
     */
    public function getCondition(QueryBuilder $queryBuilder, $dqlAlias)
    {
        $match = function ($specification) use ($queryBuilder, $dqlAlias) {
            if ($specification instanceof Condition\ModifierInterface) {
                return $specification->getCondition($queryBuilder, $dqlAlias);
            }

            return null;
        };

        $result = array_filter(array_map($match, $this->toArray()));
        if (empty($result)) {
            return null;
        }

        return call_user_func_array(
            [$queryBuilder->expr(), 'andX'],
            $result
        );
    }

    /**
     * Check to see if the current specification supports the given class
     * @param  string  $className
     * @return boolean
     */
    public function supports($className)
    {
        foreach ($this as $child) {
            if (! $child instanceof SupportInterface) {
                continue;
            }

            if (! $child->supports($className)) {
                return false;
            }
        }

        return true;
    }
}
