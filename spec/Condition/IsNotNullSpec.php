<?php

namespace spec\Rb\Specification\Doctrine\Condition;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Rb\Specification\Doctrine\SpecificationInterface;

class IsNotNullSpec extends ObjectBehavior
{
    private $field = 'foo';
    private $dqlAlias = 'a';

    public function let()
    {
        $this->beConstructedWith($this->field, $this->dqlAlias);
    }

    public function it_is_an_expression()
    {
        $this->shouldBeAnInstanceOf(SpecificationInterface::class);
    }

    public function it_calls_not_null(QueryBuilder $queryBuilder, Expr $expr)
    {
        $expression = 'a.foo is not null';

        $queryBuilder->expr()->willReturn($expr);
        $expr->isNotNull(sprintf('%s.%s', $this->dqlAlias, $this->field))->willReturn($expression);

        $this->modify($queryBuilder, null)->shouldReturn($expression);
    }

    public function it_uses_dql_alias_if_passed(QueryBuilder $queryBuilder, Expr $expr)
    {
        $dqlAlias = 'x';
        $this->beConstructedWith($this->field, null);
        $queryBuilder->expr()->willReturn($expr);

        $expr->isNotNull(sprintf('%s.%s', $dqlAlias, $this->field))->shouldBeCalled();
        $this->modify($queryBuilder, $dqlAlias);
    }
}
