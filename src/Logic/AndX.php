<?php

namespace Rb\Specification\Doctrine\Logic;

use Rb\Specification\Doctrine\SpecificationInterface;

/**
 * AndX specification lets you compose a new Specification with other specification classes
 * @package Rb\Specification\Doctrine\Logic
 */
class AndX extends Composite
{
    /**
     * @param SpecificationInterface ...$spec
     */
    public function __construct()
    {
        parent::__construct(self::AND_X, func_get_args());
    }
}
