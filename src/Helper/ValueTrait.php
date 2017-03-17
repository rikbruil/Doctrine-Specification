<?php

namespace Rb\Specification\Doctrine\Helper;

trait ValueTrait
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @return string
     */
    protected function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    protected function setValue($value)
    {
        $this->value = $value;
    }
}
