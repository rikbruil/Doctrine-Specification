<?php

namespace spec\Rb\Specification\Doctrine\Condition;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\Condition\ModifierInterface;
use PhpSpec\ObjectBehavior;

class LessThanSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('age', 18, 'a');
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
            ->shouldReturn('a.age < :comparison_10');
    }

    public function it_uses_comparison_specific_dql_alias_if_passed(
        QueryBuilder $queryBuilder,
        ArrayCollection $parameters
    ) {
        $this->beConstructedWith('age', 18, null);

        $queryBuilder->getParameters()->willReturn($parameters);
        $parameters->count()->willReturn(10);

        $queryBuilder->setParameter('comparison_10', 18)->shouldBeCalled();

        $this->getCondition($queryBuilder, 'x')->shouldReturn('x.age < :comparison_10');
    }
}
