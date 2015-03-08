<?php

namespace spec\Rb\Specification\Doctrine\Condition;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\Condition\ModifierInterface;
use Rb\Specification\Doctrine\Condition\Like;
use PhpSpec\ObjectBehavior;

class LikeSpec extends ObjectBehavior
{
    private $field = 'foo';

    private $value = 'bar';

    public function let()
    {
        $this->beConstructedWith($this->field, $this->value, Like::CONTAINS, 'dqlAlias');
    }

    public function it_is_a_specification()
    {
        $this->shouldHaveType(ModifierInterface::class);
    }

    public function it_surrounds_with_wildcards_when_using_contains(
        QueryBuilder $queryBuilder,
        ArrayCollection $parameters
    ) {
        $this->beConstructedWith($this->field, $this->value, Like::CONTAINS, 'dqlAlias');
        $queryBuilder->getParameters()->willReturn($parameters);
        $parameters->count()->willReturn(1);

        $queryBuilder->setParameter('comparison_1', '%bar%')->shouldBeCalled();

        $this->getCondition($queryBuilder, null);
    }

    public function it_starts_with_wildcard_when_using_ends_with(
        QueryBuilder $queryBuilder,
        ArrayCollection $parameters
    ) {
        $this->beConstructedWith($this->field, $this->value, Like::ENDS_WITH, 'dqlAlias');
        $queryBuilder->getParameters()->willReturn($parameters);
        $parameters->count()->willReturn(1);

        $queryBuilder->setParameter('comparison_1', '%bar')->shouldBeCalled();

        $this->getCondition($queryBuilder, null);
    }

    public function it_ends_with_wildcard_when_using_starts_with(
        QueryBuilder $queryBuilder,
        ArrayCollection $parameters
    ) {
        $this->beConstructedWith($this->field, $this->value, Like::STARTS_WITH, 'dqlAlias');
        $queryBuilder->getParameters()->willReturn($parameters);
        $parameters->count()->willReturn(1);

        $queryBuilder->setParameter('comparison_1', 'bar%')->shouldBeCalled();

        $this->getCondition($queryBuilder, null);
    }
}
