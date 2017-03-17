<?php

namespace Rb\Specification\Doctrine\Helper;

use Rb\Specification\Doctrine\Exception\InvalidArgumentException;

trait TypeCheckerTrait
{
    /**
     * @var string[]
     */
    protected $validTypes;

    /**
     * @var string
     */
    private $type;

    /**
     * @param string $type
     *
     * @throws InvalidArgumentException
     */
    public function setType($type)
    {
        if (! in_array($type, $this->validTypes, true)) {
            throw new InvalidArgumentException(sprintf(
                '"%s" is not a valid type! Valid types: %s',
                $type,
                implode(', ', $this->validTypes)
            ));
        }

        $this->type = $type;
    }
}
