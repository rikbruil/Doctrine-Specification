<?php

namespace Rb\DoctrineSpecification;

interface SupportInterface
{
    /**
     * Check to see if the current specification supports the given class
     * @param string $className
     * @return boolean
     */
    public function supports($className);
}
