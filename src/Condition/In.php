<?php

namespace Rb\Specification\Doctrine\Condition;

use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\AbstractSpecification;
use Rb\Specification\Doctrine\Helper\ParameterTrait;
use Rb\Specification\Doctrine\Helper\ValueTrait;

class In extends AbstractSpecification
{
    use ParameterTrait;
    use ValueTrait;

    /**
     * @param string      $field
     * @param mixed       $value
     * @param string|null $dqlAlias
     */
    public function __construct($field, $value, $dqlAlias = null)
    {
        $this->setValue($value);

        parent::__construct($field, $dqlAlias);
    }

    /**
     * {@inheritdoc}
     */
    public function modify(QueryBuilder $queryBuilder, $dqlAlias)
    {
        $paramName = $this->generateParameterName($queryBuilder, 'in');
        $queryBuilder->setParameter($paramName, $this->getValue());

        return (string) $queryBuilder->expr()->in(
            $this->createPropertyWithAlias($dqlAlias),
            sprintf(':%s', $paramName)
        );
    }
}
