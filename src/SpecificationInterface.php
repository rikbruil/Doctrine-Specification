<?php

namespace Rb\Doctrine\Specification;

use Rb\Doctrine\Specification\Condition;
use Rb\Doctrine\Specification\Query;

/**
 * SpecificationInterface can be used to implement custom specifications
 * @package Rb\Doctrine\Specification
 */
interface SpecificationInterface extends Condition\ModifierInterface, Query\ModifierInterface, SupportInterface
{
}
