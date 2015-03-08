<?php

namespace spec\Rb\Specification\Doctrine\Query;

use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;

class SelectSpec extends ObjectBehavior
{
    public function it_should_add_a_select_to_query_builder(QueryBuilder $queryBuilder)
    {
        $alias = 'a';
        $entity = 'foo';
        $this->beConstructedWith($entity);

        $queryBuilder->addSelect($entity)->shouldBeCalled();

        $this->modify($queryBuilder, $alias);
    }
}
