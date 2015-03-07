<?php

namespace Rb\DoctrineSpecification\Query;

use Rb\DoctrineSpecification\Exception\InvalidArgumentException;

class InnerJoin extends Join
{
    /**
     * @param string $field
     * @param string $newAlias
     * @param string|null $dqlAlias
     * @throws InvalidArgumentException
     */
    public function __construct($field, $newAlias, $dqlAlias = null)
    {
        $this->setType(self::INNER_JOIN);
        parent::__construct($field, $newAlias, $dqlAlias);
    }
}
