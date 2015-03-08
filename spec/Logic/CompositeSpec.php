<?php

namespace spec\Rb\Specification\Doctrine\Logic;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\Condition;
use Rb\Specification\Doctrine\Exception\InvalidArgumentException;
use Rb\Specification\Doctrine\Logic\Composite;
use Rb\Specification\Doctrine\SpecificationInterface;
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
        $this->shouldHaveType(Composite::class);
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

        $this->getCondition($queryBuilder, $dqlAlias);
    }

    public function it_should_return_null_for_specifications_without_conditions(
        QueryBuilder $queryBuilder,
        Expr $expression,
        SpecificationInterface $specificationA,
        Condition\ModifierInterface $specificationB
    ) {
        $this->beConstructedWith(self::EXPRESSION, [$specificationA, $specificationB]);

        $dqlAlias = 'a';

        $specificationA->getCondition($queryBuilder, $dqlAlias)->willReturn(null);
        $specificationB->getCondition($queryBuilder, $dqlAlias)->willReturn(null);
        $queryBuilder->expr()->willReturn($expression);

        $this->getCondition($queryBuilder, $dqlAlias)->shouldReturn(null);
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

    public function it_should_throw_exception_on_invalid_child()
    {
        $child = 'bar';

        $this->beConstructedWith(self::EXPRESSION, []);

        $this->shouldThrow(InvalidArgumentException::class)
            ->during('add', [$child]);
    }
}
