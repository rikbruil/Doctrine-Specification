<?php

namespace spec\Rb\DoctrineSpecification\Query;

use Doctrine\ORM\QueryBuilder;
use Rb\DoctrineSpecification\Exception\InvalidArgumentException;
use Rb\DoctrineSpecification\Query\OrderBy;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class OrderBySpec extends ObjectBehavior
{
    private $alias = 'a';
    private $field = 'foo';
    private $order = OrderBy::ASC;

    public function let()
    {
        $this->beConstructedWith($this->field, $this->order, $this->alias);
    }

    public function it_should_throw_exception_when_given_invalid_order()
    {
        $this->shouldThrow(InvalidArgumentException::class)
            ->during('__construct', ['foo', 'bar']);
    }

    public function it_should_modify_query_builder(QueryBuilder $queryBuilder)
    {
        $sort = sprintf('%s.%s', $this->alias, $this->field);

        $queryBuilder->addOrderBy($sort, $this->order)->shouldBeCalled();

        $this->modify($queryBuilder, $this->alias);
    }
}
