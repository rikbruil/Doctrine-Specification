<?php

namespace Rb\Specification\Doctrine\Condition;

use Doctrine\ORM\Query\Expr\Comparison as DoctrineComparison;
use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\AbstractSpecification;
use Rb\Specification\Doctrine\Exception\InvalidArgumentException;

class Comparison extends AbstractSpecification
{
    const EQ   = '=';
    const NEQ  = '<>';
    const LT   = '<';
    const LTE  = '<=';
    const GT   = '>';
    const GTE  = '>=';
    const LIKE = 'LIKE';

    /**
     * @var string[]
     */
    protected static $operators = [self::EQ, self::NEQ, self::LT, self::LTE, self::GT, self::GTE, self::LIKE];

    /**
     * @var string
     */
    protected $value;

    /**
     * @var string
     */
    protected $operator;

    /**
     * @param string      $operator
     * @param string      $field
     * @param string      $value
     * @param string|null $dqlAlias
     *
     * @throws InvalidArgumentException
     */
    public function __construct($operator, $field, $value, $dqlAlias = null)
    {
        if (!in_array($operator, self::$operators, true)) {
            throw new InvalidArgumentException(
                sprintf(
                    '"%s" is not a valid operator. Valid operators: %s',
                    $operator,
                    implode(', ', self::$operators)
                )
            );
        }

        $this->operator = $operator;
        $this->value    = $value;

        parent::__construct($field, $dqlAlias);
    }

    /**
     * Return a string expression which can be used as condition (in WHERE-clause).
     *
     * @param QueryBuilder $queryBuilder
     * @param string       $dqlAlias
     *
     * @return string
     */
    public function modify(QueryBuilder $queryBuilder, $dqlAlias)
    {
        $paramName = $this->generateParameterName($queryBuilder);
        $queryBuilder->setParameter($paramName, $this->value);

        return (string) new DoctrineComparison(
            $this->createPropertyWithAlias($dqlAlias),
            $this->operator,
            sprintf(':%s', $paramName)
        );
    }

    /**
     * Return automatically generated parameter name.
     *
     * @param QueryBuilder $queryBuilder
     *
     * @return string
     */
    protected function generateParameterName(QueryBuilder $queryBuilder)
    {
        return sprintf('comparison_%d', count($queryBuilder->getParameters()));
    }
}
