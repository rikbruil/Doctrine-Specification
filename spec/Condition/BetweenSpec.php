<?php

namespace spec\Rb\Specification\Doctrine\Condition;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Rb\Specification\Doctrine\SpecificationInterface;

class BetweenSpec extends ObjectBehavior
{
    private $field = 'foo';

    private $from = 1;

    private $to = 5;

    public function let()
    {
        $this->beConstructedWith($this->field, $this->from, $this->to);
    }

    public function it_is_an_expression()
    {
        $this->shouldBeAnInstanceOf(SpecificationInterface::class);
    }

    public function it_returns_an_expression_func_object(QueryBuilder $queryBuilder, ArrayCollection $parameters, Expr $expr)
    {
        $dqlAlias   = 'a';
        $expression = 'a.foo between(:from_10, :to_10)';

        $queryBuilder->expr()->willReturn($expr);
        $expr->between(sprintf('%s.%s', $dqlAlias, $this->field), ':from_10', ':to_10')->willReturn($expression);

        $queryBuilder->getParameters()->willReturn($parameters);
        $parameters->count()->willReturn(10);

        $queryBuilder->setParameter('from_10', $this->from)->shouldBeCalled();
        $queryBuilder->setParameter('to_10', $this->to)->shouldBeCalled();

        $this->isSatisfiedBy('foo')->shouldReturn(true);
        $this->modify($queryBuilder, $dqlAlias)->shouldReturn($expression);
    }

    public function it_should_use_dql_alias_if_set(QueryBuilder $queryBuilder, ArrayCollection $parameters, Expr $expr)
    {
        $dqlAlias   = 'x';
        $expression = 'x.foo between(:from_10, :to_10)';

        $this->beConstructedWith($this->field, $this->from, $this->to, $dqlAlias);

        $queryBuilder->expr()->willReturn($expr);
        $expr->between(sprintf('%s.%s', $dqlAlias, $this->field), ':from_10', ':to_10')->willReturn($expression);

        $queryBuilder->getParameters()->willReturn($parameters);
        $parameters->count()->willReturn(10);

        $queryBuilder->setParameter('from_10', $this->from)->shouldBeCalled();
        $queryBuilder->setParameter('to_10', $this->to)->shouldBeCalled();

        $this->isSatisfiedBy('foo')->shouldReturn(true);
        $this->modify($queryBuilder, $dqlAlias)->shouldReturn($expression);
    }
}
