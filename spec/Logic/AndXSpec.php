<?php

namespace spec\Rb\Specification\Doctrine\Logic;

use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Rb\Specification\Doctrine\Condition\ModifierInterface;
use Rb\Specification\Doctrine\Logic\Composite;

class AndXSpec extends ObjectBehavior
{
    public function it_should_have_correct_types()
    {
        $this->shouldHaveType(ModifierInterface::class);
        $this->shouldHaveType(Composite::class);
        $this->shouldHaveType(ArrayCollection::class);
    }
}
