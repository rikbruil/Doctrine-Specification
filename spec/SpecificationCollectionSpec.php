<?php

namespace spec\Rb\Specification\Doctrine;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\Condition;
use Rb\Specification\Doctrine\Exception\InvalidArgumentException;
use Rb\Specification\Doctrine\SpecificationInterface;
use Rb\Specification\Doctrine\Query;
use PhpSpec\ObjectBehavior;

class SpecificationCollectionSpec extends ObjectBehavior
{
    public function it_is_a_specification()
    {
        $this->shouldHaveType(SpecificationInterface::class);
    }

    public function it_modifies_all_child_queries(
        QueryBuilder $queryBuilder,
        SpecificationInterface $specificationA,
        SpecificationInterface $specificationB
    ) {
        $this->beConstructedWith([$specificationA, $specificationB]);
        $dqlAlias = 'a';

        $specificationA->modify($queryBuilder, $dqlAlias)->shouldBeCalled();
        $specificationB->modify($queryBuilder, $dqlAlias)->shouldBeCalled();

        $this->modify($queryBuilder, $dqlAlias);
    }

    public function it_supports_conditions(
        QueryBuilder $queryBuilder,
        Expr $expression,
        Condition\ModifierInterface $conditionA,
        Condition\ModifierInterface $conditionB,
        $x,
        $y
    ) {
        $dqlAlias = 'a';

        $this[] = $conditionA;
        $this[] = $conditionB;

        $conditionA->getCondition($queryBuilder, $dqlAlias)->willReturn($x);
        $conditionB->getCondition($queryBuilder, $dqlAlias)->willReturn($y);
        $queryBuilder->expr()->willReturn($expression);

        $expression->andX($x, $y)->shouldBeCalled();

        $this->supports('foo')->shouldReturn(true);
        $this->getCondition($queryBuilder, $dqlAlias);
        $this->modify($queryBuilder, $dqlAlias);
    }

    public function it_supports_query_modifiers(
        QueryBuilder $queryBuilder,
        Query\ModifierInterface $modifierA,
        Query\ModifierInterface $modifierB
    ) {
        $this->beConstructedWith([$modifierA, $modifierB]);

        $dqlAlias = 'a';

        $modifierA->modify($queryBuilder, $dqlAlias)->shouldBeCalled();
        $modifierB->modify($queryBuilder, $dqlAlias)->shouldBeCalled();

        $this->supports('foo')->shouldReturn(true);
        $this->getCondition($queryBuilder, $dqlAlias)->shouldReturn(null);
        $this->modify($queryBuilder, $dqlAlias);
    }

    public function it_should_throw_exception_when_child_does_not_support_class(
        SpecificationInterface $specificationA,
        SpecificationInterface $specificationB
    ) {
        $className = 'foo';
        $this->beConstructedWith([$specificationA, $specificationB]);

        $specificationA->supports($className)->willReturn(true);
        $specificationB->supports($className)->willReturn(false);

        $this->supports($className)->shouldReturn(false);
    }

    public function it_should_throw_exception_on_invalid_child()
    {
        $this->shouldThrow(InvalidArgumentException::class)
            ->during('add', ['bar']);
    }
}
