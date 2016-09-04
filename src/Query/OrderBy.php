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
    protected $order;

    /**
     * @param string      $field
     * @param string      $order
     * @param string|null $dqlAlias
     *
     * @throws InvalidArgumentException
     */
    public function __construct($field, $order = null, $dqlAlias = null)
    {
        $order = ! $order ? self::ASC : strtoupper($order);

        if (! in_array($order, self::$validOrder, true)) {
            throw new InvalidArgumentException();
        }

        $this->order = $order;

        parent::__construct($field, $dqlAlias);
    }

    /**
     * {@inheritdoc}
     */
    public function modify(QueryBuilder $queryBuilder, $dqlAlias)
    {
        $queryBuilder->addOrderBy(
            $this->createPropertyWithAlias($dqlAlias),
            $this->order
        );
    }
}
