<?php

namespace spec\Rb\Specification\Doctrine\Result;

use Doctrine\ORM\AbstractQuery;
use Rb\Specification\Doctrine\Exception\InvalidArgumentException;
use Rb\Specification\Doctrine\Result;
use Rb\Specification\Doctrine\Query;
use PhpSpec\ObjectBehavior;

class ModifierCollectionSpec extends ObjectBehavior
{
    public function it_should_call_modify_on_child_modifiers(
        Result\ModifierInterface $modifierA,
        Result\ModifierInterface $modifierB,
        AbstractQuery $query
    ) {
        $this->beConstructedWith($modifierA, $modifierB);

        $modifierA->modify($query)->shouldBeCalled();
        $modifierB->modify($query)->shouldBeCalled();

        $this->modify($query);
    }

    public function it_should_throw_exception_when_adding_incorrect_children(Query\ModifierInterface $queryModifier)
    {
        $this->shouldThrow(InvalidArgumentException::class)
            ->during('add', [$queryModifier]);
    }
}
