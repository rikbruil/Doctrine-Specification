<?php

namespace spec\Rb\Doctrine\Specification\Logic;

use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Rb\Doctrine\Specification\Logic\Composite;
use Rb\Doctrine\Specification\SpecificationInterface;

class AndXSpec extends ObjectBehavior
{
    public function it_should_have_correct_types()
    {
        $this->shouldHaveType(SpecificationInterface::class);
        $this->shouldHaveType(Composite::class);
        $this->shouldHaveType(ArrayCollection::class);
    }
}
