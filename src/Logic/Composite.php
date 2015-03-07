<?php

namespace Rb\Doctrine\Specification\Logic;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Rb\Doctrine\Specification\Condition;
use Rb\Doctrine\Specification\Query;
use Rb\Doctrine\Specification\Exception\InvalidArgumentException;
use Rb\Doctrine\Specification\SpecificationInterface;
use Rb\Doctrine\Specification\SupportInterface;

/**
 * Class Composite
 * @package Rb\Doctrine\Specification\Logic
 */
class Composite extends ArrayCollection implements SpecificationInterface
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
     * @param string                                                $type
     * @param Query\ModifierInterface[]|Condition\ModifierInterface $children
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
        if (! $value instanceof Query\ModifierInterface &&
            ! $value instanceof Condition\ModifierInterface
        ) {
            throw new InvalidArgumentException(sprintf(
                '"%s" does not implement any ModifierInterface!',
                (is_object($value)) ? get_class($value) : $value
            ));
        }

        return parent::add($value);
    }

    /**
     * @param  Query\ModifierInterface[]|Condition\ModifierInterface[] $children
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
        $match = function ($specification) use ($queryBuilder, $dqlAlias) {
            if ($specification instanceof Condition\ModifierInterface) {
                return $specification->getCondition($queryBuilder, $dqlAlias);
            }

            return null;
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

    /**
     * @param QueryBuilder $queryBuilder
     * @param string       $dqlAlias
     */
    public function modify(QueryBuilder $queryBuilder, $dqlAlias)
    {
        foreach ($this as $child) {
            if (!$child instanceof Query\ModifierInterface) {
                continue;
            }

            $child->modify($queryBuilder, $dqlAlias);
        }
    }

    /**
     * Check to see if the current specification supports the given class
     * @param  string  $className
     * @return boolean
     */
    public function supports($className)
    {
        foreach ($this as $child) {
            if (!$child instanceof SupportInterface) {
                continue;
            }

            if (!$child->supports($className)) {
                return false;
            }
        }

        return true;
    }
}
