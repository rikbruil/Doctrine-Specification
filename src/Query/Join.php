<?php

namespace Rb\DoctrineSpecification\Query;

use Doctrine\ORM\QueryBuilder;
use Rb\DoctrineSpecification\Exception\InvalidArgumentException;

/**
 * Class Join
 * @package Rb\DoctrineSpecification\Query
 */
class Join implements ModifierInterface
{
    const JOIN = 'join';
    const LEFT_JOIN = 'leftJoin';
    const INNER_JOIN = 'innerJoin';

    /**
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
     * @var string
     */
    private $type = self::JOIN;

    /**
     * @param string $field
     * @param string $newAlias
     * @param string|null $dqlAlias
     * @throws InvalidArgumentException
     */
    public function __construct($field, $newAlias, $dqlAlias = null)
    {
        $this->field = $field;
        $this->newAlias = $newAlias;
        $this->dqlAlias = $dqlAlias;
    }

    /**
     * @param string $type
     * @throws InvalidArgumentException
     */
    public function setType($type)
    {
        if (! in_array($type, self::$types)) {
            throw new InvalidArgumentException(sprintf(
                '"%s" is not a valid type! Valid types: %s',
                $type,
                implode(', ', self::$types)
            ));
        }

        $this->type = $type;
    }

    /**
     * Method to modify the given QueryBuilder object
     * @param QueryBuilder $queryBuilder
     * @param string $dqlAlias
     * @return void
     */
    public function modify(QueryBuilder $queryBuilder, $dqlAlias)
    {
        if (! is_null($this->dqlAlias)) {
            $dqlAlias = $this->dqlAlias;
        }

        $statement = sprintf('%s.%s', $dqlAlias, $this->field);

        call_user_func_array(
            [$queryBuilder, $this->type],
            [$statement, $this->newAlias]
        );
    }
}
