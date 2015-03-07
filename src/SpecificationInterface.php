<?php

namespace Rb\DoctrineSpecification;

use Rb\DoctrineSpecification\Condition;
use Rb\DoctrineSpecification\Query;

/**
 * SpecificationInterface can be used to implement custom specifications
 * @package Rb\Doctrine\Specification
 */
interface SpecificationInterface extends Condition\ModifierInterface, Query\ModifierInterface, SupportInterface
{
}
