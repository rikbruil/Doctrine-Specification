<?php

namespace Rb\Specification\Doctrine\Query;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\Exception\InvalidArgumentException;

/**
 * Class ModifierCollection
 * @package Rb\Specification\Doctrine\Query
 */
class ModifierCollection extends ArrayCollection implements ModifierInterface
{
    /**
     * @param ModifierInterface ...$queryModifier
     */
    public function __construct()
    {
        array_map([$this, 'add'], func_get_args());
    }

    /**
     * @param  ModifierInterface        $value
     * @return bool
     * @throws InvalidArgumentException
     */
    public function add($value)
    {
        if (! $value instanceof ModifierInterface) {
            throw new InvalidArgumentException(sprintf(
                '"%s" does not implement "%s"!',
                get_class($value),
                ModifierInterface::class
            ));
        }

        return parent::add($value);
    }

    /**
     * Method to modify the given QueryBuilder object
     * @param  QueryBuilder             $queryBuilder
     * @param  string                   $dqlAlias
     * @throws InvalidArgumentException
     */
    public function modify(QueryBuilder $queryBuilder, $dqlAlias)
    {
        foreach ($this as $child) {
            $child->modify($queryBuilder, $dqlAlias);
        }
    }
}
