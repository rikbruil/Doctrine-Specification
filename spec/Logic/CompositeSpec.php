<?php

namespace spec\Rb\Doctrine\Specification\Logic;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Rb\Doctrine\Specification\Condition;
use Rb\Doctrine\Specification\Exception\InvalidArgumentException;
use Rb\Doctrine\Specification\SpecificationInterface;
use Rb\Doctrine\Specification\Query;
use PhpSpec\ObjectBehavior;

class CompositeSpec extends ObjectBehavior
{
    const EXPRESSION = 'andX';

    public function let(SpecificationInterface $specificationA, SpecificationInterface $specificationB)
    {
        $this->beConstructedWith(self::EXPRESSION, [$specificationA, $specificationB]);
    }

    public function it_is_a_specification()
    {
        $this->shouldHaveType(SpecificationInterface::class);
    }

    public function it_modifies_all_child_queries(
        QueryBuilder $queryBuilder,
        SpecificationInterface $specificationA,
        SpecificationInterface $specificationB
    ) {
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
        $this->beConstructedWith(self::EXPRESSION, [$conditionA, $conditionB]);

        $dqlAlias = 'a';
        $condition = 'condition';

        $conditionA->getCondition($queryBuilder, $dqlAlias)->willReturn($x);
        $conditionB->getCondition($queryBuilder, $dqlAlias)->willReturn($y);
        $queryBuilder->expr()->willReturn($expression);

        $expression->{self::EXPRESSION}($x, $y)->shouldBeCalled();

        $this->supports('foo')->shouldReturn(true);
        $this->getCondition($queryBuilder, $dqlAlias);
        $this->modify($queryBuilder, $dqlAlias);
    }

    public function it_supports_query_modifiers(
        QueryBuilder $queryBuilder,
        Query\ModifierInterface $modifierA,
        Query\ModifierInterface $modifierB
    ) {
        $this->beConstructedWith(self::EXPRESSION, [$modifierA, $modifierB]);

        $dqlAlias = 'a';

        $modifierA->modify($queryBuilder, $dqlAlias)->shouldBeCalled();
        $modifierB->modify($queryBuilder, $dqlAlias)->shouldBeCalled();

        $this->supports('foo')->shouldReturn(true);
        $this->getCondition($queryBuilder, $dqlAlias)->shouldReturn(null);
        $this->modify($queryBuilder, $dqlAlias);
    }

    public function it_should_throw_exception_on_invalid_type(
        SpecificationInterface $specificationA,
        SpecificationInterface $specificationB
    ) {
        $type = 'foo';

        $this->beConstructedWith($type, [$specificationA, $specificationB]);

        $this->shouldThrow(InvalidArgumentException::class)
            ->during('__construct', [$type, [$specificationA, $specificationB]]);
    }

    public function it_should_throw_exception_when_child_does_not_support_class(
        SpecificationInterface $specificationA,
        SpecificationInterface $specificationB
    ) {
        $className = 'foo';
        $this->beConstructedWith(self::EXPRESSION, [$specificationA, $specificationB]);

        $specificationA->supports($className)->willReturn(true);
        $specificationB->supports($className)->willReturn(false);

        $this->supports($className)->shouldReturn(false);
    }

    public function it_should_throw_exception_on_invalid_child()
    {
        $child = 'bar';

        $this->beConstructedWith(self::EXPRESSION, []);

        $this->shouldThrow(InvalidArgumentException::class)
            ->during('add', [$child]);
    }
}
