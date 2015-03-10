<?php

namespace spec\Rb\Specification\Doctrine\Logic;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Rb\Specification\Doctrine\SpecificationInterface;

class NotSpec extends ObjectBehavior
{
    public function let(SpecificationInterface $condition)
    {
        $this->beConstructedWith($condition, null);
    }

    public function it_calls_parent_match(QueryBuilder $queryBuilder, Expr $expr, SpecificationInterface $condition)
    {
        $dqlAlias         = 'a';
        $expression       = 'expression';
        $parentExpression = 'foo';

        $queryBuilder->expr()->willReturn($expr);
        $condition->modify($queryBuilder, $dqlAlias)->willReturn($parentExpression);

        $expr->not($parentExpression)->willReturn($expression);

        $this->modify($queryBuilder, $dqlAlias)->shouldReturn($expression);
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

        $specification->isSatisfiedBy($className)->shouldBeCalled();

        $this->isSatisfiedBy($className);
    }

    public function it_does_not_modify_parent_query(QueryBuilder $queryBuilder)
    {
        $this->modify($queryBuilder, 'a');
    }
}
