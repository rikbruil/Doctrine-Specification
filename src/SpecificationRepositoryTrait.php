<?php

namespace Rb\Specification\Doctrine;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\Exception\LogicException;
use Rb\Specification\Doctrine\Result\ModifierInterface;

/**
 * Class SpecificationRepositoryTrait.
 */
trait SpecificationRepositoryTrait
{
    /**
     * @var string
     */
    protected $dqlAlias = 'e';

    /**
     * @see SpecificationAwareInterface::match()
     *
     * @param SpecificationInterface $specification
     * @param ModifierInterface|null $modifier
     *
     * @throws LogicException
     *
     * @return Query
     */
    public function match(SpecificationInterface $specification, ModifierInterface $modifier = null)
    {
        if (! $specification->isSatisfiedBy($this->getEntityName())) {
            throw new LogicException(sprintf(
                'Specification "%s" not supported by this repository!',
                get_class($specification)
            ));
        }

        $queryBuilder = $this->createQueryBuilder($this->dqlAlias);
        $this->modifyQueryBuilder($queryBuilder, $specification);

        return $this->modifyQuery($queryBuilder, $modifier);
    }

    /**
     * Modifies the QueryBuilder according to the passed Specification.
     * Will also set the condition for this query if needed.
     *
     * @param QueryBuilder           $queryBuilder
     * @param SpecificationInterface $specification
     *
     * @internal param string $dqlAlias
     */
    private function modifyQueryBuilder(QueryBuilder $queryBuilder, SpecificationInterface $specification)
    {
        $condition = $specification->modify($queryBuilder, $this->dqlAlias);

        if (empty($condition)) {
            return;
        }

        $queryBuilder->where($condition);
    }

    /**
     * Modifies and returns a Query object according to the (optional) result modifier.
     *
     * @param QueryBuilder           $queryBuilder
     * @param ModifierInterface|null $modifier
     *
     * @return Query
     */
    private function modifyQuery(QueryBuilder $queryBuilder, ModifierInterface $modifier = null)
    {
        $query = $queryBuilder->getQuery();

        if ($modifier) {
            $modifier->modify($query);
        }

        return $query;
    }
}
