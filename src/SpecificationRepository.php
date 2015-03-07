<?php

namespace Rb\Doctrine\Specification;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Rb\Doctrine\Specification\Exception\LogicException;
use Rb\Doctrine\Specification\Result\ModifierInterface;

/**
 * Class SpecificationRepository
 * @package Rb\Doctrine\Specification
 */
class SpecificationRepository extends EntityRepository
{
    /**
     * @var string
     */
    private $dqlAlias = 'e';

    /**
     * Get the query after matching with given specification
     * @param SpecificationInterface $specification
     * @param ModifierInterface $resultModifier
     * @return Query
     * @throws LogicException
     */
    public function match(SpecificationInterface $specification, ModifierInterface $resultModifier = null)
    {
        if (! $specification->supports($this->getEntityName())) {
            throw new LogicException('Specification not supported by this repository!');
        }

        $dqlAlias = $this->dqlAlias;
        $queryBuilder = $this->createQueryBuilder($dqlAlias);

        $specification->modify($queryBuilder, $dqlAlias);

        $condition = $specification->getCondition($queryBuilder, $dqlAlias);
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
