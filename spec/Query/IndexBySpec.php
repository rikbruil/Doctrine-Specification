<?php

namespace spec\Rb\Specification\Doctrine\Query;

use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;

class IndexBySpec extends ObjectBehavior
{
    private $alias = 'a';
    private $field = 'foo';

    public function let()
    {
        $this->beConstructedWith($this->field, $this->alias);
    }

    public function it_should_modify_query_builder(QueryBuilder $queryBuilder)
    {
        $property = sprintf('%s.%s', $this->alias, $this->field);

        $queryBuilder->indexBy($this->alias, $property)->shouldBeCalled();

        $this->isSatisfiedBy('foo')->shouldReturn(true);
        $this->modify($queryBuilder, $this->alias);
    }
}
