<?php

namespace Rb\DoctrineSpecification\Condition;

use Rb\DoctrineSpecification\Exception\InvalidArgumentException;

class GreaterThanOrEquals extends Comparison
{
    /**
     * @param string $field
     * @param string $value
     * @param string|null $dqlAlias
     * @throws InvalidArgumentException
     */
    public function __construct($field, $value, $dqlAlias = null)
    {
        parent::__construct(self::GTE, $field, $value, $dqlAlias);
    }
}
