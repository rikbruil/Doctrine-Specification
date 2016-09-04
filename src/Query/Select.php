<?php

namespace Rb\Specification\Doctrine\Query;

use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\Exception\InvalidArgumentException;
use Rb\Specification\Doctrine\SpecificationInterface;

/**
 * Select will modify the query-builder so you can specify SELECT-statements.
 */
class Select implements SpecificationInterface
{
    const SELECT     = 'select';
    const ADD_SELECT = 'addSelect';

    protected static $types = [self::SELECT, self::ADD_SELECT];

    /**
     * @var string|array
     */
    protected $select;

    /**
     * @var string
     */
    protected $type;

    /**
     * @param string|array $select
     * @param string       $type
     */
    public function __construct($select, $type = self::ADD_SELECT)
    {
        $this->setType($type);
        $this->select = $select;
    }

    /**
     * {@inheritdoc}
     */
    public function modify(QueryBuilder $queryBuilder, $dqlAlias)
    {
        call_user_func_array([$queryBuilder, $this->type], [$this->select]);
    }

    /**
     * @param string $type
     *
     * @throws InvalidArgumentException
     */
    public function setType($type)
    {
        if (!in_array($type, self::$types, true)) {
            throw new InvalidArgumentException(sprintf(
                '"%s" is not a valid type! Valid types: %s',
                $type,
                implode(', ', self::$types)
            ));
        }

        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($value)
    {
        return true;
    }
}
