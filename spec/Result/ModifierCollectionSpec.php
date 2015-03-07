<?php

namespace spec\Rb\DoctrineSpecification\Result;

use Doctrine\ORM\AbstractQuery;
use Rb\DoctrineSpecification\Exception\InvalidArgumentException;
use Rb\DoctrineSpecification\Result;
use Rb\DoctrineSpecification\Query;
use Rb\DoctrineSpecification\SpecificationInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

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
