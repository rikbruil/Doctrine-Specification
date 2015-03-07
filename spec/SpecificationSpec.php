<?php

namespace spec\Rb\Doctrine\Specification;

use Doctrine\ORM\QueryBuilder;
use Rb\Doctrine\Specification\Condition;
use Rb\Doctrine\Specification\Exception\InvalidArgumentException;
use Rb\Doctrine\Specification\Query;
use Rb\Doctrine\Specification\Result;
use Rb\Doctrine\Specification\SpecificationInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

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
