<?php

namespace Rb\Specification\Doctrine\Condition;

use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\SpecificationInterface;

class Between implements SpecificationInterface
{
    /**
     * @var string
     */
    protected $field;

    /**
     * @var mixed
     */
    protected $from;

    /**
     * @var mixed
     */
    protected $to;

    /**
     * @var null|string
     */
    protected $dqlAlias;

    /**
     * @param string      $field
     * @param mixed       $from
     * @param mixed       $to
     * @param string|null $dqlAlias
     */
    public function __construct($field, $from, $to, $dqlAlias = null)
    {
        $this->field    = $field;
        $this->from     = $from;
        $this->to       = $to;
        $this->dqlAlias = $dqlAlias;
    }

    /**
     * {@inheritDoc}
     */
    public function isSatisfiedBy($value)
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function modify(QueryBuilder $queryBuilder, $dqlAlias)
    {
        if (! empty($this->dqlAlias)) {
            $dqlAlias = $this->dqlAlias;
        }

        $fromParam = $this->generateParameterName('from', $queryBuilder);
        $toParam   = $this->generateParameterName('to', $queryBuilder);

        $queryBuilder->setParameter($fromParam, $this->from);
        $queryBuilder->setParameter($toParam, $this->to);

        return (string) $queryBuilder->expr()->between(
            sprintf('%s.%s', $dqlAlias, $this->field),
            sprintf(':%s', $fromParam),
            sprintf(':%s', $toParam)
        );
    }

    /**
     * @param string       $type
     * @param QueryBuilder $queryBuilder
     *
     * @return string
     */
    private function generateParameterName($type, QueryBuilder $queryBuilder)
    {
        return sprintf('%s_%d', $type, count($queryBuilder->getParameters()));
    }
}
