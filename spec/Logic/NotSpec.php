<?php

namespace spec\Rb\Doctrine\Specification\Logic;

use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Rb\Doctrine\Specification\Condition\ModifierInterface;
use Rb\Doctrine\Specification\SpecificationInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NotSpec extends ObjectBehavior
{
    public function let(ModifierInterface $condition)
    {
        $this->beConstructedWith($condition, null);
    }

    public function it_calls_parent_match(QueryBuilder $queryBuilder, Expr $expr, ModifierInterface $condition)
    {
        $dqlAlias = 'a';
        $expression = 'expression';
        $parentExpression = 'foo';

        $queryBuilder->expr()->willReturn($expr);
        $condition->getCondition($queryBuilder, $dqlAlias)->willReturn($parentExpression);

        $expr->not($parentExpression)->willReturn($expression);

        $this->getCondition($queryBuilder, $dqlAlias)->shouldReturn($expression);
    }

    public function it_modifies_parent_query(QueryBuilder $queryBuilder, SpecificationInterface $specification)
    {
        $dqlAlias = 'a';
        $this->beConstructedWith($specification, null);

        $specification->modify($queryBuilder, $dqlAlias)->shouldBeCalled();
        $this->modify($queryBuilder, $dqlAlias);
    }

    public function it_should_call_supports_on_parent(SpecificationInterface $specification)
    {
        $className = 'foo';
        $this->beConstructedWith($specification, null);

        $specification->supports($className)->shouldBeCalled();

        $this->supports($className);
    }

    public function it_should_support_classes_without_support_interface(ModifierInterface $modifier)
    {
        $this->beConstructedWith($modifier, null);
        $this->supports('foo')->shouldReturn(true);
    }

    public function it_does_not_modify_parent_query(QueryBuilder $queryBuilder)
    {
        $this->modify($queryBuilder, 'a');
    }
}
