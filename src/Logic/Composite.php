<?php

namespace Rb\Specification\Doctrine\Logic;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\Condition;
use Rb\Specification\Doctrine\Query;
use Rb\Specification\Doctrine\Exception\InvalidArgumentException;

/**
 * Class Composite
 * @package Rb\Specification\Doctrine\Logic
 */
class Composite extends ArrayCollection implements Condition\ModifierInterface
{
    const AND_X = 'andX';
    const OR_X = 'orX';

    /**
     * @var string[]
     */
    protected static $types = [self::OR_X, self::AND_X];

    /**
     * @var string
     */
    private $type;

    /**
     * @param string                        $type
     * @param Condition\ModifierInterface[] $children
     */
    public function __construct($type, array $children = [])
    {
        $this->setType($type)
            ->setChildren($children);
    }

    /**
     * Set the type of comparison
     * @param  string                   $type
     * @return $this
     * @throws InvalidArgumentException
     */
    protected function setType($type)
    {
        if (!in_array($type, self::$types)) {
            $message = sprintf('"%s" is not a valid type! Valid types: %s', $type, implode(', ', self::$types));
            throw new InvalidArgumentException($message);
        }

        $this->type = $type;

        return $this;
    }

    /**
     * @param  Query\ModifierInterface|Condition\ModifierInterface $value
     * @return bool
     * @throws InvalidArgumentException
     */
    public function add($value)
    {
        if (! $value instanceof Condition\ModifierInterface) {
            throw new InvalidArgumentException(sprintf(
                '"%s" does not implement "%s"!',
                (is_object($value)) ? get_class($value) : $value,
                Condition\ModifierInterface::class
            ));
        }

        return parent::add($value);
    }

    /**
     * @param  Condition\ModifierInterface[] $children
     * @return $this
     */
    protected function setChildren(array $children)
    {
        $this->clear();
        array_map([$this, 'add'], $children);

        return $this;
    }

    /**
     * Try to match this specification with the query builder
     * @param  QueryBuilder $queryBuilder
     * @param  string       $dqlAlias
     * @return Expr
     */
    public function getCondition(QueryBuilder $queryBuilder, $dqlAlias)
    {
        /**
         * @param Condition\ModifierInterface $modifier
         * @return string|null
         */
        $match = function ($modifier) use ($queryBuilder, $dqlAlias) {
            return $modifier->getCondition($queryBuilder, $dqlAlias);
        };

        $result = array_filter(array_map($match, $this->toArray()));
        if (empty($result)) {
            return null;
        }

        return call_user_func_array(
            [$queryBuilder->expr(), $this->type],
            $result
        );
    }
}
