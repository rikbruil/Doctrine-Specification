<?php

namespace spec\Rb\Specification\Doctrine\Logic;

use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Rb\Specification\Doctrine\Logic\Composite;
use Rb\Specification\Doctrine\SpecificationInterface;

class OrXSpec extends ObjectBehavior
{
    public function it_should_have_correct_types()
    {
        $this->shouldHaveType(SpecificationInterface::class);
        $this->shouldHaveType(Composite::class);
        $this->shouldHaveType(ArrayCollection::class);
    }
}
