<?php

namespace spec\Rb\Specification\Doctrine\Query;

use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Rb\Specification\Doctrine\Exception\InvalidArgumentException;
use Rb\Specification\Doctrine\Query\GroupBy;

class GroupBySpec extends ObjectBehavior
{
    private $alias = 'a';
    private $field = 'foo';

    public function it_should_support_anything()
    {
        $this->beConstructedWith($this->field, GroupBy::ADD_GROUP_BY, $this->alias);

        $this->isSatisfiedBy('foo')->shouldReturn(true);
    }

    public function it_calls_groupBy_on_query_builder(QueryBuilder $queryBuilder)
    {
        $this->beConstructedWith($this->field, GroupBy::GROUP_BY, $this->alias);

        $arg = sprintf('%s.%s', $this->alias, $this->field);
        $queryBuilder->groupBy($arg)->shouldBeCalled();

        $this->modify($queryBuilder, $this->alias);
    }

    public function it_calls_addGroupBy_on_query_builder(QueryBuilder $queryBuilder)
    {
        $this->beConstructedWith($this->field, GroupBy::ADD_GROUP_BY, $this->alias);

        $arg = sprintf('%s.%s', $this->alias, $this->field);
        $queryBuilder->addGroupBy($arg)->shouldBeCalled();

        $this->modify($queryBuilder, $this->alias);
    }

    public function it_throws_exception_when_setting_illegal_type()
    {
        $this->beConstructedWith($this->field, 'foo', $this->alias);
        $this->shouldThrow(InvalidArgumentException::class)->duringInstantiation();
    }
}
