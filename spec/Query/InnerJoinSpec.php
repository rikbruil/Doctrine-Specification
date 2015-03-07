<?php

namespace spec\Rb\Doctrine\Specification\Query;

use Doctrine\ORM\QueryBuilder;
use Rb\Doctrine\Specification\Query\ModifierInterface;
use PhpSpec\ObjectBehavior;

class InnerJoinSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('user', 'authUser', 'a');
    }

    public function it_is_a_specification()
    {
        $this->shouldHaveType(ModifierInterface::class);
    }

    public function it_joins_with_default_dql_alias(QueryBuilder $queryBuilder)
    {
        $queryBuilder->innerJoin('a.user', 'authUser')->shouldBeCalled();
        $this->modify($queryBuilder, 'a');
    }

    public function it_uses_local_alias_if_global_was_not_set(QueryBuilder $queryBuilder)
    {
        $this->beConstructedWith('user', 'authUser');
        $queryBuilder->innerJoin('b.user', 'authUser')->shouldBeCalled();
        $this->modify($queryBuilder, 'b');
    }
}
