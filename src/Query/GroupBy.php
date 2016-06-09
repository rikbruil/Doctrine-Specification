<?php

namespace Rb\Specification\Doctrine\Query;

use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\AbstractSpecification;
use Rb\Specification\Doctrine\Exception\InvalidArgumentException;


/**
 * @author  Kyle Tucker <kyleatucker@gmail.com>
 */
class GroupBy extends AbstractSpecification
{
    const GROUP_BY     = 'groupBy';
    const ADD_GROUP_BY = 'addGroupBy';

    /** @var  string[]  */
    protected static $types = [self::GROUP_BY, self::ADD_GROUP_BY];

    /** @var  string */
    protected $type;

    /**
     * Constructor.
     *
     * @param  string       $field
     * @param  string       $type
     * @param  string|null  $dqlAlias
     */
    public function __construct($field, $type = self::ADD_GROUP_BY, $dqlAlias = null)
    {
        $this->setType($type);
        parent::__construct($field, $dqlAlias);
    }

    /**
     * {@inheritdoc}
     */
    public function modify(QueryBuilder $queryBuilder, $dqlAlias)
    {
        call_user_func_array(
            [$queryBuilder, $this->type],
            [
                $this->createPropertyWithAlias($dqlAlias),
            ]
        );
    }

    /**
     * @param  string  $type
     *
     * @throws  InvalidArgumentException
     */
    public function setType($type)
    {
        if (!in_array($type, self::$types)) {
            throw new InvalidArgumentException(sprintf(
                '"%s" is not a valid type! Valid types: %s',
                $type,
                implode(', ', self::$types)
            ));
        }

        $this->type = $type;
    }
}
