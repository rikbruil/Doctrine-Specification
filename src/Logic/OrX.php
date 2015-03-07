<?php

namespace Rb\DoctrineSpecification\Logic;

use Rb\DoctrineSpecification\SpecificationInterface;

/**
 * Class OrX
 * @package Rb\Doctrine\Specification\Logic
 */
class OrX extends Composite
{
    /**
     * @param SpecificationInterface ...$spec
     */
    public function __construct()
    {
        parent::__construct(self::OR_X, func_get_args());
    }
}
