<?php

namespace Rb\Specification\Doctrine\Logic;

use Rb\Specification\Doctrine\Exception\InvalidArgumentException;
use Rb\Specification\Doctrine\Specification;
use Rb\Specification\Doctrine\SpecificationInterface;

/**
 * Class Composite.
 */
class Composite extends Specification
{
    const AND_X = 'andX';
    const OR_X  = 'orX';

    /**
     * @var string[]
     */
    protected static $types = [self::OR_X, self::AND_X];

    /**
     * @var string
     */
    private $type;

    /**
     * @param string                   $type
     * @param SpecificationInterface[] $children
     */
    public function __construct($type, array $children = [])
    {
        $this->setType($type)
            ->setChildren($children);
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
