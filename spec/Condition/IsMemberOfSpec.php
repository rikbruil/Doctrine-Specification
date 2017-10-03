<?php

namespace spec\Rb\Specification\Doctrine\Condition;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Rb\Specification\Doctrine\SpecificationInterface;

class IsMemberOfSpec extends ObjectBehavior
{
    private $className = '\Foo';
    private $field     = 'foo';

    private $dqlAlias = 'a';

    public function let()
    {
        $this->beConstructedWith($this->field, $this->className, $this->dqlAlias);
    }

    public function it_is_an_expression()
    {
        $this->shouldBeAnInstanceOf(SpecificationInterface::class);
    }

    public function it_calls_is_instance_of(QueryBuilder $queryBuilder, Expr $expr)
    {
        $expression = 'a.foo member of ' . $this->className;

        $queryBuilder->expr()->willReturn($expr);
        $expr->isMemberOf(sprintf('%s.%s', $this->dqlAlias, $this->field), $this->className)->willReturn($expression);

        $this->isSatisfiedBy('foo')->shouldReturn(true);
        $this->modify($queryBuilder, 'b')->shouldReturn($expression);
    }

    public function it_uses_dql_alias_if_passed(QueryBuilder $queryBuilder, Expr $expr)
    {
        $dqlAlias = 'x';
        $this->beConstructedWith($this->field, $this->className, null);
        $queryBuilder->expr()->willReturn($expr);

        $expr->isMemberOf(sprintf('%s.%s', $dqlAlias, $this->field), $this->className)->shouldBeCalled();

        $this->isSatisfiedBy('foo')->shouldReturn(true);
        $this->modify($queryBuilder, $dqlAlias);
    }
}
