<?php

namespace spec\Rb\Doctrine\Specification\Condition;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Rb\Doctrine\Specification\Condition\ModifierInterface;
use PhpSpec\ObjectBehavior;

class InSpec extends ObjectBehavior
{
    private $field = 'foo';

    private $value = ['bar', 'baz'];

    public function let()
    {
        $this->beConstructedWith($this->field, $this->value);
    }

    public function it_is_an_expression()
    {
        $this->shouldBeAnInstanceOf(ModifierInterface::class);
    }

    public function it_returns_an_expression_func_object(QueryBuilder $queryBuilder, ArrayCollection $parameters, Expr $expr)
    {
        $dqlAlias = 'a';
        $expression = 'a.foo in(:in_10)';

        $queryBuilder->expr()->willReturn($expr);
        $expr->in(sprintf('%s.%s', $dqlAlias, $this->field), ':in_10')->willReturn($expression);

        $queryBuilder->getParameters()->willReturn($parameters);
        $parameters->count()->willReturn(10);

        $queryBuilder->setParameter('in_10', $this->value)->shouldBeCalled();
        $this->getCondition($queryBuilder, $dqlAlias)->shouldReturn($expression);
    }

    public function it_should_use_dql_alias_if_set(QueryBuilder $queryBuilder, ArrayCollection $parameters, Expr $expr)
    {
        $dqlAlias = 'x';
        $expression = 'x.foo in(:in_10)';

        $this->beConstructedWith($this->field, $this->value, $dqlAlias);

        $queryBuilder->expr()->willReturn($expr);
        $expr->in(sprintf('%s.%s', $dqlAlias, $this->field), ':in_10')->willReturn($expression);

        $queryBuilder->getParameters()->willReturn($parameters);
        $parameters->count()->willReturn(10);

        $queryBuilder->setParameter('in_10', $this->value)->shouldBeCalled();
        $this->getCondition($queryBuilder, $dqlAlias)->shouldReturn($expression);
    }
}
