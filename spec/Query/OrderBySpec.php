<?php

namespace spec\Rb\Doctrine\Specification\Query;

use Doctrine\ORM\QueryBuilder;
use Rb\Doctrine\Specification\Exception\InvalidArgumentException;
use Rb\Doctrine\Specification\Query\OrderBy;
use PhpSpec\ObjectBehavior;

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
