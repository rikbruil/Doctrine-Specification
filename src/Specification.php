<?php

namespace Rb\Specification\Doctrine;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\Exception\InvalidArgumentException;

/**
 * Specification can be used as a quick-start to writing your own specifications.
 * It extends Doctrines ArrayCollection class so you can compose specifications.
 */
class Specification extends ArrayCollection implements SpecificationInterface
{
    const AND_X = 'andX';
    const OR_X  = 'orX';

    protected static $types = [self::OR_X, self::AND_X];

    /**
     * @var string
     */
    private $type = self::AND_X;

    /**
     * @param SpecificationInterface[] $elements
     */
    public function __construct(array $elements = [])
    {
        parent::__construct();

        $this->setChildren($elements);
    }

    /**
     * @param SpecificationInterface $value
     *
     * @throws InvalidArgumentException
     *
     * @return bool
     */
    public function add($value)
    {
        if (! $value instanceof SpecificationInterface) {
            throw new InvalidArgumentException(sprintf(
                '"%s" does not implement "%s"!',
                (is_object($value)) ? get_class($value) : $value,
                SpecificationInterface::class
            ));
        }

        return parent::add($value);
    }

    /**
     * {@inheritdoc}
     */
    public function modify(QueryBuilder $queryBuilder, $dqlAlias)
    {
        $match = function (SpecificationInterface $specification) use ($queryBuilder, $dqlAlias) {
            return $specification->modify($queryBuilder, $dqlAlias);
        };

        $result = array_filter(array_map($match, $this->toArray()));
        if (empty($result)) {
            return;
        }

        return call_user_func_array(
            [$queryBuilder->expr(), $this->type],
            $result
        );
    }

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($value)
    {
        /** @var SpecificationInterface $child */
        foreach ($this as $child) {
            if ($child->isSatisfiedBy($value)) {
                continue;
            }

            return false;
        }

        return true;
    }

    /**
     * @param SpecificationInterface[] $children
     *
     * @return $this
     */
    protected function setChildren(array $children)
    {
        $this->clear();
        array_map([$this, 'add'], $children);

        return $this;
    }

    /**
     * Set the type of comparison.
     *
     * @param string $type
     *
     * @throws InvalidArgumentException
     *
     * @return $this
     */
    protected function setType($type)
    {
        if (! in_array($type, self::$types, true)) {
            $message = sprintf('"%s" is not a valid type! Valid types: %s', $type, implode(', ', self::$types));
            throw new InvalidArgumentException($message);
        }

        $this->type = $type;

        return $this;
    }
}
