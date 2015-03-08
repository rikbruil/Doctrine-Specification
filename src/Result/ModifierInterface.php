<?php

namespace Rb\Specification\Doctrine\Result;

use Doctrine\ORM\AbstractQuery;

/**
 * Interface ModifierInterface
 * @package Rb\Specification\Doctrine\Result
 */
interface ModifierInterface
{
    /**
     * Modify the query (e.g. select more fields/relations)
     * @param  AbstractQuery $query
     * @return void
     */
    public function modify(AbstractQuery $query);
}
