<?php

namespace Rb\Specification\Doctrine\Query;

use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\Exception\InvalidArgumentException;
use Rb\Specification\Doctrine\SpecificationInterface;

/**
 * Class OrderBy.
 */
class OrderBy implements SpecificationInterface
{
    const ASC  = 'ASC';
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
     * @param string      $field
     * @param string      $order
     * @param string|null $dqlAlias
     *
     * @throws InvalidArgumentException
     */
    public function __construct($field, $order = self::ASC, $dqlAlias = null)
    {
        if (! in_array($order, self::$validOrder)) {
            throw new InvalidArgumentException();
        }

        $this->field    = $field;
        $this->order    = $order;
        $this->dqlAlias = $dqlAlias;
    }

    /**
     * {@inheritDoc}
     */
    public function modify(QueryBuilder $queryBuilder, $dqlAlias)
    {
        if (! is_null($this->dqlAlias)) {
            $dqlAlias = $this->dqlAlias;
        }

        $orderBy = sprintf('%s.%s', $dqlAlias, $this->field);
        $queryBuilder->addOrderBy($orderBy, $this->order);
    }

    /**
     * {@inheritDoc}
     */
    public function isSatisfiedBy($value)
    {
        return true;
    }
}
