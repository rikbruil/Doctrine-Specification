<?php

namespace Rb\Specification\Doctrine;

/**
 * SpecificationInterface can be used to implement custom specifications
 * @package Rb\Specification\Doctrine
 */
interface SpecificationInterface extends Condition\ModifierInterface, Query\ModifierInterface, SupportInterface
{
}
