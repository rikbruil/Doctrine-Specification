<?php

namespace Rb\Specification\Doctrine;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Rb\Specification\Doctrine\Exception\LogicException;
use Rb\Specification\Doctrine\Result\ModifierInterface;

/**
 * Class SpecificationRepository
 * @package Rb\Specification\Doctrine
 */
class SpecificationRepository extends EntityRepository
{
    /**
     * @var string
     */
    private $dqlAlias = 'e';

    /**
     * Get the query after matching with given specification
     * @param  SpecificationInterface $specification
     * @param  ModifierInterface      $resultModifier
     * @return Query
     * @throws LogicException
     */
    public function match(SpecificationInterface $specification, ModifierInterface $resultModifier = null)
    {
        if (! $specification->isSatisfiedBy($this->getEntityName())) {
            throw new LogicException(sprintf(
                'Specification "%s" not supported by this repository!',
                get_class($specification)
            ));
        }

        $dqlAlias = $this->dqlAlias;
        $queryBuilder = $this->createQueryBuilder($dqlAlias);
        $condition = $specification->modify($queryBuilder, $dqlAlias);

        if (! empty($condition)) {
            $queryBuilder->where($condition);
        }

        $query = $queryBuilder->getQuery();
        if ($resultModifier) {
            $resultModifier->modify($query);
        }

        return $query;
    }
}
