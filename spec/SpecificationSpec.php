<?php

namespace spec\Rb\Specification\Doctrine;

use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\Condition;
use Rb\Specification\Doctrine\Exception\InvalidArgumentException;
use Rb\Specification\Doctrine\Query;
use Rb\Specification\Doctrine\Result;
use Rb\Specification\Doctrine\SpecificationInterface;
use PhpSpec\ObjectBehavior;

class SpecificationSpec extends ObjectBehavior
{
    private $alias = 's';
    private $className = 'foo';
    private $condition = 'condition';

    public function it_should_accept_other_specifications(
        QueryBuilder $queryBuilder,
        SpecificationInterface $specification
    ) {
        $specification->supports($this->className)->willReturn(true);
        $specification->getCondition($queryBuilder, $this->alias)->willReturn($this->condition);
        $specification->modify($queryBuilder, $this->alias)->shouldBeCalled();

        $this->setSpecification($specification);

        $this->supports($this->className)->shouldReturn(true);
        $this->getCondition($queryBuilder, $this->alias)->shouldReturn($this->condition);
        $this->modify($queryBuilder, $this->alias);
    }

    public function it_should_accept_query_modifiers(QueryBuilder $queryBuilder, Query\ModifierInterface $modifier)
    {
        $modifier->modify($queryBuilder, $this->alias)->shouldBeCalled();

        $this->setSpecification($modifier);

        $this->supports($this->className)->shouldReturn(true);
        $this->getCondition($queryBuilder, $this->alias)->shouldReturn('');
        $this->modify($queryBuilder, $this->alias);
    }

    public function it_should_accept_condition_modifiers(
        QueryBuilder $queryBuilder,
        Condition\ModifierInterface $modifier
    ) {
        $modifier->getCondition($queryBuilder, $this->alias)->willReturn($this->condition);

        $this->setSpecification($modifier);

        $this->supports($this->className)->shouldReturn(true);
        $this->getCondition($queryBuilder, $this->alias)->shouldReturn($this->condition);
        $this->modify($queryBuilder, $this->alias);
    }

    public function it_should_throw_an_exception_when_setting_incorrect_specification(
        Result\ModifierInterface $modifier
    ) {
        $this->shouldThrow(InvalidArgumentException::class)
            ->during('setSpecification', [$modifier]);
    }
}
