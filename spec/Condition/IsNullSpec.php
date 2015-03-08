<?php

namespace spec\Rb\Specification\Doctrine\Condition;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\Condition\ModifierInterface;
use PhpSpec\ObjectBehavior;

class IsNullSpec extends ObjectBehavior
{
    private $field = 'foo';

    private $dqlAlias = 'a';

    public function let()
    {
        $this->beConstructedWith($this->field, $this->dqlAlias);
    }

    public function it_is_an_expression()
    {
        $this->shouldBeAnInstanceOf(ModifierInterface::class);
    }

    public function it_calls_null(QueryBuilder $queryBuilder, Expr $expr)
    {
        $expression = 'a.foo is null';

        $queryBuilder->expr()->willReturn($expr);
        $expr->isNull(sprintf('%s.%s', $this->dqlAlias, $this->field))->willReturn($expression);

        $this->getCondition($queryBuilder, 'b')->shouldReturn($expression);
    }

    public function it_uses_dql_alias_if_passed(QueryBuilder $queryBuilder, Expr $expr)
    {
        $dqlAlias = 'x';
        $this->beConstructedWith($this->field, null);
        $queryBuilder->expr()->willReturn($expr);

        $expr->isNull(sprintf('%s.%s', $dqlAlias, $this->field))->shouldBeCalled();

        $this->getCondition($queryBuilder, $dqlAlias);
    }
}
