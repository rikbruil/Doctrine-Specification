<?php

namespace spec\Rb\Specification\Doctrine\Query;

use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Rb\Specification\Doctrine\SpecificationInterface;

class InnerJoinSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('user', 'authUser', 'a');
    }

    public function it_is_a_specification()
    {
        $this->shouldHaveType(SpecificationInterface::class);
    }

    public function it_joins_with_default_dql_alias(QueryBuilder $queryBuilder)
    {
        $queryBuilder->innerJoin('a.user', 'authUser', null, null, null)->shouldBeCalled();
        $this->modify($queryBuilder, 'a');
    }

    public function it_uses_local_alias_if_global_was_not_set(QueryBuilder $queryBuilder)
    {
        $this->beConstructedWith('user', 'authUser');
        $queryBuilder->innerJoin('b.user', 'authUser', null, null, null)->shouldBeCalled();
        $this->modify($queryBuilder, 'b');
    }
}
