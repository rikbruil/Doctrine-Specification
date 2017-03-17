<?php

namespace Rb\Specification\Doctrine;

use Doctrine\ORM\EntityRepository;

class SpecificationRepositoryStub extends EntityRepository
{
    use SpecificationRepositoryTrait;
}
