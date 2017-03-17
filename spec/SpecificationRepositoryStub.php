<?php

namespace Rb\Specification\Doctrine;

use Doctrine\ORM\EntityRepository;

class SpecificationRepositoryStub extends EntityRepository implements SpecificationAwareInterface
{
    use SpecificationRepositoryTrait;
}
