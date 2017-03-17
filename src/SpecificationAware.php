<?php

namespace Rb\Specification\Doctrine;

use Doctrine\ORM\Query;
use Rb\Specification\Doctrine\Result\ModifierInterface;

/**
 * SpecificationAware can be used to implement custom repository.
 */
interface SpecificationAware
{
    /**
     * Get the query after matching with given specification.
     *
     * @param SpecificationInterface $specification
     * @param ModifierInterface      $modifier
     *
     * @return Query
     */
    public function match(SpecificationInterface $specification, ModifierInterface $modifier = null);
}
