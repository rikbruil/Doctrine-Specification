<?php

namespace Rb\Doctrine\Specification\Query;

use Rb\Doctrine\Specification\Exception\InvalidArgumentException;

class InnerJoin extends Join
{
    /**
     * @param  string                   $field
     * @param  string                   $newAlias
     * @param  string|null              $dqlAlias
     * @throws InvalidArgumentException
     */
    public function __construct($field, $newAlias, $dqlAlias = null)
    {
        $this->setType(self::INNER_JOIN);
        parent::__construct($field, $newAlias, $dqlAlias);
    }
}
