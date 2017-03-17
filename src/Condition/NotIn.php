<?php

namespace Rb\Specification\Doctrine\Condition;

use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\AbstractSpecification;
use Rb\Specification\Doctrine\Helper\ParameterTrait;
use Rb\Specification\Doctrine\Helper\ValueTrait;

class NotIn extends AbstractSpecification
{
    use ValueTrait;
    use ParameterTrait;

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

    public function modify(QueryBuilder $queryBuilder, $dqlAlias)
    {
        $paramName = $this->generateParameterName($queryBuilder, 'not_in');
        $queryBuilder->setParameter($paramName, $this->getValue());

        return (string) $queryBuilder->expr()->notIn(
            $this->createPropertyWithAlias($dqlAlias),
            sprintf(':%s', $paramName)
        );
    }
}
