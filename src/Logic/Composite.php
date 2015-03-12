<?php

namespace Rb\Specification\Doctrine\Logic;

use Rb\Specification\Doctrine\Specification;
use Rb\Specification\Doctrine\SpecificationInterface;

/**
 * Class Composite.
 */
class Composite extends Specification
{
    /**
     * @param string                   $type
     * @param SpecificationInterface[] $children
     */
    public function __construct($type, array $children = [])
    {
        $this->setType($type)
            ->setChildren($children);
    }
}
