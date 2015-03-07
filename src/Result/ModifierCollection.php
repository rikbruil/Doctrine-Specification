<?php

namespace Rb\Doctrine\Specification\Result;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\AbstractQuery;
use Rb\Doctrine\Specification\Exception\InvalidArgumentException;

/**
 * CollectionResultModifierInterface allows to compose one/more ResultModifier classes
 * @package Rb\Doctrine\Specification\Result
 */
class ModifierCollection extends ArrayCollection implements ModifierInterface
{
    /**
     * Compose one or more ResultModifier and evaluate as a single modifier
     * @param ModifierInterface ...$modifiers
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
     * Modify the query (e.g. select more fields/relations)
     * @param  AbstractQuery            $query
     * @throws InvalidArgumentException
     */
    public function modify(AbstractQuery $query)
    {
        foreach ($this as $child) {
            $child->modify($query);
        }
    }
}
