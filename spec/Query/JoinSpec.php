<?php

namespace spec\Rb\Specification\Doctrine\Query;

use Doctrine\ORM\Query\Expr\Join as DoctrineJoin;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Rb\Specification\Doctrine\Exception\InvalidArgumentException;
use Rb\Specification\Doctrine\Query\Join;
use Rb\Specification\Doctrine\SpecificationInterface;

class JoinSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('user', 'authUser', 'a');
    }

    public function it_is_a_specification()
    {
        $this->shouldHaveType(SpecificationInterface::class);
    }

    public function it_should_support_anything()
    {
        $this->isSatisfiedBy('foo')->shouldReturn(true);
    }

    public function it_joins_with_default_dql_alias(QueryBuilder $queryBuilder)
    {
        $queryBuilder->join('a.user', 'authUser', null, null, null)->shouldBeCalled();
        $this->modify($queryBuilder, 'a');
    }

    public function it_uses_local_alias_if_global_was_not_set(QueryBuilder $queryBuilder)
    {
        $this->beConstructedWith('user', 'authUser');
        $queryBuilder->join('b.user', 'authUser', null, null, null)->shouldBeCalled();
        $this->modify($queryBuilder, 'b');
    }

    public function it_should_use_be_able_to_use_join_conditions(QueryBuilder $queryBuilder)
    {
        $joinType      = DoctrineJoin::ON;
        $joinCondition = 'join condition';

        $this->beConstructedWith('user', 'authUser');

        $this->setConditionType($joinType);
        $this->setCondition($joinCondition);

        $queryBuilder->join('a.user', 'authUser', $joinType, $joinCondition, null)->shouldBeCalled();

        $this->modify($queryBuilder, 'a');
    }

    public function it_should_be_able_to_set_index_by_for_join(QueryBuilder $queryBuilder)
    {
        $indexedBy = 'index_by';

        $this->beConstructedWith('user', 'authUser');

        $queryBuilder->join('a.user', 'authUser', null, null, $indexedBy)->shouldBeCalled();

        $this->setIndexedBy($indexedBy);

        $this->modify($queryBuilder, 'a');
    }

    public function it_throws_an_exception_when_setting_illegal_type()
    {
        $this->setType(Join::LEFT_JOIN);

        $this->shouldThrow(InvalidArgumentException::class)
            ->during('setType', ['foo']);
    }
}
