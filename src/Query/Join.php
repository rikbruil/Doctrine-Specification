<?php

namespace Rb\Specification\Doctrine\Query;

use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\Exception\InvalidArgumentException;
use Rb\Specification\Doctrine\SpecificationInterface;

/**
 * Class Join.
 */
class Join implements SpecificationInterface
{
    const JOIN       = 'join';
    const LEFT_JOIN  = 'leftJoin';
    const INNER_JOIN = 'innerJoin';

    /*
     * @var string[]
     */
    static protected $types = [self::JOIN, self::LEFT_JOIN, self::INNER_JOIN];

    /**
     * @var string
     */
    private $field;

    /**
     * @var string
     */
    private $newAlias;

    /**
     * @var string|null
     */
    private $dqlAlias;

    /**
     * @var string|null
     */
    private $conditionType = null;

    /**
     * @var string|null
     */
    private $condition = null;

    /**
     * @var string|null
     */
    private $indexedBy = null;

    /**
     * @var string
     */
    private $type = self::JOIN;

    /**
     * @param string      $field
     * @param string      $newAlias
     * @param string|null $dqlAlias
     *
     * @throws InvalidArgumentException
     */
    public function __construct($field, $newAlias, $dqlAlias = null)
    {
        $this->field    = $field;
        $this->newAlias = $newAlias;
        $this->dqlAlias = $dqlAlias;
    }

    /**
     * @param string $type
     *
     * @throws InvalidArgumentException
     */
    public function setType($type)
    {
        if (! in_array($type, self::$types, true)) {
            throw new InvalidArgumentException(sprintf(
                '"%s" is not a valid type! Valid types: %s',
                $type,
                implode(', ', self::$types)
            ));
        }

        $this->type = $type;
    }

    /**
     * {@inheritDoc}
     */
    public function modify(QueryBuilder $queryBuilder, $dqlAlias)
    {
        if (! is_null($this->dqlAlias)) {
            $dqlAlias = $this->dqlAlias;
        }

        $statement = sprintf('%s.%s', $dqlAlias, $this->field);

        call_user_func_array(
            [$queryBuilder, $this->type],
            [$statement, $this->newAlias, $this->conditionType, $this->condition, $this->indexedBy]
        );
    }

    /**
     * {@inheritDoc}
     */
    public function isSatisfiedBy($value)
    {
        return true;
    }

    /**
     * Set the condition type to be used on the join (WITH/ON).
     *
     * @param string $conditionType
     */
    public function setConditionType($conditionType)
    {
        $this->conditionType = $conditionType;
    }

    /**
     * Set the condition to be used for the join statement.
     *
     * @param string $condition
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;
    }

    /**
     * Set the property which will be used as index for the returned collection.
     *
     * @param mixed $indexedBy
     */
    public function setIndexedBy($indexedBy)
    {
        $this->indexedBy = $indexedBy;
    }
}
