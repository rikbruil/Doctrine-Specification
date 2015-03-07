<?php

namespace Rb\Doctrine\Specification\Query;

use Doctrine\ORM\QueryBuilder;
use Rb\Doctrine\Specification\Exception\InvalidArgumentException;

/**
 * Class OrderBy
 * @package Rb\Doctrine\Specification\Query
 */
class OrderBy implements ModifierInterface
{
    const ASC = 'ASC';
    const DESC = 'DESC';

    static private $validOrder = [self::ASC, self::DESC];

    /**
     * @var string
     */
    protected $field;

    /**
     * @var string
     */
    protected $order;

    /**
     * @var null|string
     */
    protected $dqlAlias;

    /**
     * @param  string                   $field
     * @param  string                   $order
     * @param  string|null              $dqlAlias
     * @throws InvalidArgumentException
     */
    public function __construct($field, $order = self::ASC, $dqlAlias = null)
    {
        if (! in_array($order, self::$validOrder)) {
            throw new InvalidArgumentException();
        }

        $this->field = $field;
        $this->order = $order;
        $this->dqlAlias = $dqlAlias;
    }

    /**
     * Method to modify the given QueryBuilder object
     * @param  QueryBuilder $queryBuilder
     * @param  string       $dqlAlias
     * @return void
     */
    public function modify(QueryBuilder $queryBuilder, $dqlAlias)
    {
        if (! is_null($this->dqlAlias)) {
            $dqlAlias = $this->dqlAlias;
        }

        $orderBy = sprintf('%s.%s', $dqlAlias, $this->field);
        $queryBuilder->addOrderBy($orderBy, $this->order);
    }
}
