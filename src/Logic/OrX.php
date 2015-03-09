<?php

namespace Rb\Specification\Doctrine\Logic;

use Rb\Specification\Doctrine\SpecificationInterface;

/**
 * Class OrX.
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
