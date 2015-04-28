<?php

namespace Rb\Specification\Doctrine\Query;

use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\AbstractSpecification;
use Rb\Specification\Doctrine\Exception\InvalidArgumentException;

/**
 * Class OrderBy.
 */
class OrderBy extends AbstractSpecification
{
    const ASC  = 'ASC';
    const DESC = 'DESC';

    private static $validOrder = [self::ASC, self::DESC];

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
        if (!in_array($order, self::$validOrder, true)) {
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
        $queryBuilder->addOrderBy(
            $this->createPropertyWithAlias($dqlAlias),
            $this->order
        );
    }
}
