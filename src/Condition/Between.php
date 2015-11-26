<?php

namespace Rb\Specification\Doctrine\Condition;

use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\AbstractSpecification;

class Between extends AbstractSpecification
{
    /**
     * @var mixed
     */
    protected $from;

    /**
     * @var mixed
     */
    protected $to;

    /**
     * @param string      $field
     * @param mixed       $from
     * @param mixed       $to
     * @param string|null $dqlAlias
     */
    public function __construct($field, $from, $to, $dqlAlias = null)
    {
        $this->from = $from;
        $this->to   = $to;

        parent::__construct($field, $dqlAlias);
    }

    /**
     * {@inheritdoc}
     */
    public function modify(QueryBuilder $queryBuilder, $dqlAlias)
    {
        $fromParam = $this->generateParameterName('from', $queryBuilder);
        $toParam   = $this->generateParameterName('to', $queryBuilder);

        $queryBuilder->setParameter($fromParam, $this->from);
        $queryBuilder->setParameter($toParam, $this->to);

        return (string) $queryBuilder->expr()->between(
            $this->createPropertyWithAlias($dqlAlias),
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
