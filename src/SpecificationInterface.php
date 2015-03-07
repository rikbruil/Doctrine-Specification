<?php

namespace Rb\Doctrine\Specification;

/**
 * SpecificationInterface can be used to implement custom specifications
 * @package Rb\Doctrine\Specification
 */
interface SpecificationInterface extends Condition\ModifierInterface, Query\ModifierInterface, SupportInterface
{
}
