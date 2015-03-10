<?php

namespace spec\Rb\Specification\Doctrine\Condition;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Rb\Specification\Doctrine\Condition\Comparison;
use Rb\Specification\Doctrine\Exception\InvalidArgumentException;
use Rb\Specification\Doctrine\SpecificationInterface;

class ComparisonSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(Comparison::GT, 'age', 18, 'a');
    }

    public function it_is_an_expression()
    {
        $this->shouldBeAnInstanceOf(SpecificationInterface::class);
    }

    public function it_returns_comparison_object(QueryBuilder $queryBuilder, ArrayCollection $parameters)
    {
        $queryBuilder->getParameters()->willReturn($parameters);
        $parameters->count()->willReturn(10);

        $queryBuilder->setParameter('comparison_10', 18)->shouldBeCalled();

        $this->isSatisfiedBy('foo')->shouldReturn(true);
        $this->modify($queryBuilder, null)
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

        $this->isSatisfiedBy('foo')->shouldReturn(true);
        $this->modify($queryBuilder, 'x')->shouldReturn('x.age > :comparison_10');
    }

    public function it_validates_operator()
    {
        $this->shouldThrow(InvalidArgumentException::class)
            ->during('__construct', ['==', 'age', 18, null]);
    }
}
