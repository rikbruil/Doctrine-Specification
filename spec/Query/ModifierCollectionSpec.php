<?php

namespace spec\Rb\Specification\Doctrine\Query;

use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\Exception\InvalidArgumentException;
use Rb\Specification\Doctrine\Query\ModifierInterface;
use Rb\Specification\Doctrine\Result;
use PhpSpec\ObjectBehavior;

class ModifierCollectionSpec extends ObjectBehavior
{
    public function it_should_call_modify_on_child_modifiers(
        ModifierInterface $modifierA,
        ModifierInterface $modifierB,
        QueryBuilder $queryBuilder
    ) {
        $dqlAlias = 'a';
        $this->beConstructedWith($modifierA, $modifierB);

        $modifierA->modify($queryBuilder, $dqlAlias)->shouldBeCalled();
        $modifierB->modify($queryBuilder, $dqlAlias)->shouldBeCalled();

        $this->modify($queryBuilder, $dqlAlias);
    }

    public function it_should_throw_exception_when_adding_incorrect_children(Result\ModifierInterface $resultModifier)
    {
        $this->shouldThrow(InvalidArgumentException::class)
            ->during('add', [$resultModifier]);
    }
}
