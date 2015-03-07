<?php

namespace Rb\DoctrineSpecification\Query;

use Rb\DoctrineSpecification\Exception\InvalidArgumentException;

class LeftJoin extends Join
{
    /**
     * @param string $field
     * @param string $newAlias
     * @param string|null $dqlAlias
     * @throws InvalidArgumentException
     */
    public function __construct($field, $newAlias, $dqlAlias = null)
    {
        $this->setType(self::LEFT_JOIN);
        parent::__construct($field, $newAlias, $dqlAlias);
    }
}
