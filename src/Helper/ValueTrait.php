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
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}
