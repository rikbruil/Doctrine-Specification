<?php

namespace Rb\Specification\Doctrine;

use Doctrine\ORM\EntityRepository;

/**
 * This class is only here for backwards compatibility reasons.
 *
 * The preferred way of interacting with specification objects is by writing your own
 * custom repository class that uses the SpecificationRepositoryTrait.
 *
 * The SpecificationAwareInterface is added in case you need it.
 *
 * @see SpecificationRepositoryTrait
 * @see SpecificationAwareInterface
 */
class SpecificationRepository extends EntityRepository implements SpecificationAwareInterface
{
    use SpecificationRepositoryTrait;
}
