<?php

namespace Rb\Specification\Doctrine\Condition;

use Doctrine\ORM\Query\Expr\Comparison as DoctrineComparison;
use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\Exception\InvalidArgumentException;
use Rb\Specification\Doctrine\SpecificationInterface;

class Comparison implements SpecificationInterface
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
    protected $field;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var string|null
     */
    protected $dqlAlias;

    /**
     * @var string
     */
    private $operator;

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
        if (! in_array($operator, self::$operators, true)) {
            throw new InvalidArgumentException(
                sprintf(
                    '"%s" is not a valid operator. Valid operators: %s',
                    $operator,
                    implode(', ', self::$operators)
                )
            );
        }

        $this->operator = $operator;
        $this->field    = $field;
        $this->value    = $value;
        $this->dqlAlias = $dqlAlias;
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
        if ($this->dqlAlias) {
            $dqlAlias = $this->dqlAlias;
        }

        $paramName = $this->generateName($queryBuilder);
        $queryBuilder->setParameter($paramName, $this->value);

        return (string) new DoctrineComparison(
            sprintf('%s.%s', $dqlAlias, $this->field),
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
    protected function generateName(QueryBuilder $queryBuilder)
    {
        return sprintf('comparison_%d', count($queryBuilder->getParameters()));
    }

    /**
     * {@inheritDoc}
     */
    public function isSatisfiedBy($value)
    {
        return true;
    }
}
