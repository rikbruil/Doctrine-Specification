<?php

namespace spec\Rb\Specification\Doctrine;

use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Rb\Specification\Doctrine\Condition;
use Rb\Specification\Doctrine\Exception\InvalidArgumentException;
use Rb\Specification\Doctrine\Result;
use Rb\Specification\Doctrine\SpecificationInterface;

class SpecificationSpec extends ObjectBehavior
{
    private $alias     = 's';
    private $className = 'foo';
    private $condition = 'condition';

    public function it_should_accept_other_specifications(
        QueryBuilder $queryBuilder,
        SpecificationInterface $specification
    ) {
        $specification->isSatisfiedBy($this->className)->willReturn(true);
        $specification->modify($queryBuilder, $this->alias)->willReturn($this->condition);

        $this->setSpecification($specification);

        $this->isSatisfiedBy($this->className)->shouldReturn(true);
        $this->modify($queryBuilder, $this->alias)->shouldReturn($this->condition);
    }

    public function it_should_accept_query_modifiers(QueryBuilder $queryBuilder, SpecificationInterface $modifier)
    {
        $modifier->isSatisfiedBy($this->className)->willReturn(true);
        $modifier->modify($queryBuilder, $this->alias)->shouldBeCalled();

        $this->setSpecification($modifier);

        $this->isSatisfiedBy($this->className)->shouldReturn(true);
        $this->modify($queryBuilder, $this->alias)->shouldReturn('');
    }

    public function it_should_accept_condition_modifiers(
        QueryBuilder $queryBuilder,
        SpecificationInterface $modifier
    ) {
        $modifier->isSatisfiedBy($this->className)->willReturn(true);
        $modifier->modify($queryBuilder, $this->alias)->willReturn($this->condition);

        $this->setSpecification($modifier);

        $this->isSatisfiedBy($this->className)->shouldReturn(true);
        $this->modify($queryBuilder, $this->alias)->shouldReturn($this->condition);
    }

    public function it_should_throw_an_exception_when_setting_incorrect_specification(
        Result\ModifierInterface $modifier
    ) {
        $this->shouldThrow(InvalidArgumentException::class)
            ->during('setSpecification', [$modifier]);
    }
}
