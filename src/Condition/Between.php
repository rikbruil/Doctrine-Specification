<?php

namespace Rb\Specification\Doctrine\Condition;

use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\AbstractSpecification;
use Rb\Specification\Doctrine\Helper\ParameterTrait;

class Between extends AbstractSpecification
{
    use ParameterTrait;

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
        $fromParam = $this->generateParameterName($queryBuilder, 'from');
        $toParam   = $this->generateParameterName($queryBuilder, 'to');

        $queryBuilder->setParameter($fromParam, $this->from);
        $queryBuilder->setParameter($toParam, $this->to);

        return (string) $queryBuilder->expr()->between(
            $this->createPropertyWithAlias($dqlAlias),
            sprintf(':%s', $fromParam),
            sprintf(':%s', $toParam)
        );
    }
}
