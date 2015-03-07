<?php

namespace spec\Rb\Doctrine\Specification\Condition;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use Rb\Doctrine\Specification\Condition\Comparison;
use Rb\Doctrine\Specification\Condition\ModifierInterface;
use Rb\Doctrine\Specification\Exception\InvalidArgumentException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ComparisonSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(Comparison::GT, 'age', 18, 'a');
    }

    public function it_is_an_expression()
    {
        $this->shouldBeAnInstanceOf(ModifierInterface::class);
    }

    public function it_returns_comparison_object(QueryBuilder $queryBuilder, ArrayCollection $parameters)
    {
        $queryBuilder->getParameters()->willReturn($parameters);
        $parameters->count()->willReturn(10);

        $queryBuilder->setParameter('comparison_10', 18)->shouldBeCalled();

        $condition = $this->getCondition($queryBuilder, null)
            ->shouldReturn('a.age > :comparison_10');
    }

    public function it_uses_comparison_specific_dql_alias_if_passed(
        QueryBuilder $queryBuilder,
        ArrayCollection $parameters
    ) {
        $this->beConstructedWith(Comparison::GT, 'age', 18, null);

        $queryBuilder->getParameters()->willReturn($parameters);
        $parameters->count()->willReturn(10);

        $queryBuilder->setParameter('comparison_10', 18)->shouldBeCalled();

        $this->getCondition($queryBuilder, 'x')->shouldReturn('x.age > :comparison_10');
    }

    public function it_validates_operator()
    {
        $this->shouldThrow(InvalidArgumentException::class)
            ->during('__construct', ['==', 'age', 18, null]);
    }
}
