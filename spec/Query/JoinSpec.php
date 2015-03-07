<?php

namespace spec\Rb\DoctrineSpecification\Query;

use Doctrine\ORM\QueryBuilder;
use Rb\DoctrineSpecification\Exception\InvalidArgumentException;
use Rb\DoctrineSpecification\Query\Join;
use Rb\DoctrineSpecification\Query\ModifierInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class JoinSpec extends ObjectBehavior
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
        $queryBuilder->join('a.user', 'authUser')->shouldBeCalled();
        $this->modify($queryBuilder, 'a');
    }

    public function it_uses_local_alias_if_global_was_not_set(QueryBuilder $queryBuilder)
    {
        $this->beConstructedWith('user', 'authUser');
        $queryBuilder->join('b.user', 'authUser')->shouldBeCalled();
        $this->modify($queryBuilder, 'b');
    }

    public function it_throws_an_exception_when_setting_illegal_type()
    {
        $this->setType(Join::LEFT_JOIN);

        $this->shouldThrow(InvalidArgumentException::class)
            ->during('setType', ['foo']);
    }
}
