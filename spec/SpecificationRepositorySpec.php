<?php

namespace spec\Rb\DoctrineSpecification;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use Rb\DoctrineSpecification\Exception\LogicException;
use Rb\DoctrineSpecification\Logic\AndX;
use Rb\DoctrineSpecification\Result\ModifierInterface;
use Rb\DoctrineSpecification\SpecificationInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SpecificationRepositorySpec extends ObjectBehavior
{
    private $dqlAlias = 'e';

    private $expression = 'expr';

    private $result = 'result';

    public function let(EntityManager $entityManager, ClassMetadata $classMetadata)
    {
        $this->beConstructedWith($entityManager, $classMetadata);
    }

    public function it_matches_a_specification_without_result_modifier(
        SpecificationInterface $specification,
        EntityManager $entityManager,
        QueryBuilder $queryBuilder,
        AbstractQuery $query
    ) {
        $this->prepare($specification, $entityManager, $queryBuilder, $query);
        $specification->supports(Argument::any())
            ->willReturn(true);

        $specification->modify($queryBuilder, $this->dqlAlias)
            ->shouldBeCalled();

        $this->match($specification)
            ->shouldReturn($query);

        $query->execute();
    }

    public function it_matches_a_specification_with_result_modifier(
        SpecificationInterface $specification,
        EntityManager $entityManager,
        QueryBuilder $queryBuilder,
        AbstractQuery $query,
        ModifierInterface $modifier
    ) {
        $this->prepare($specification, $entityManager, $queryBuilder, $query);
        $specification->supports(Argument::any())
            ->willReturn(true);

        $specification->modify($queryBuilder, $this->dqlAlias)
            ->shouldBeCalled();
        $modifier->modify($query)
            ->shouldBeCalled();

        $this->match($specification, $modifier)
            ->shouldReturn($query);

        $query->execute();
    }

    public function it_should_throw_logic_exception_if_spec_not_supported(
        SpecificationInterface $specification,
        EntityManager $entityManager,
        QueryBuilder $queryBuilder,
        AbstractQuery $query
    ) {
        $this->prepare($specification, $entityManager, $queryBuilder, $query);
        $specification->supports(Argument::any())
            ->willReturn(false);

        $this->shouldThrow(LogicException::class)
            ->during('match', [$specification, null]);
    }

    public function it_should_accept_specification_with_only_query_modifiers(
        SpecificationInterface $specification,
        EntityManager $entityManager,
        QueryBuilder $queryBuilder,
        AbstractQuery $query
    ) {
        $entityManager->createQueryBuilder()->willReturn($queryBuilder);

        $queryBuilder->select($this->dqlAlias)->willReturn($queryBuilder);
        $queryBuilder->from(Argument::any(), $this->dqlAlias)->willReturn($queryBuilder);
        $queryBuilder->where(Argument::any())->shouldNotBeCalled();
        $queryBuilder->getQuery()->willReturn($query);

        $specification->modify($queryBuilder, $this->dqlAlias)->shouldBeCalled();
        $specification->supports(Argument::any())->willReturn(true);
        $specification->getCondition($queryBuilder, $this->dqlAlias)->willReturn('');

        $this->match($specification);
    }

    /**
     * Prepare mocks
     * @param SpecificationInterface $specification
     * @param EntityManager $entityManager
     * @param QueryBuilder $queryBuilder
     * @param AbstractQuery $query
     */
    private function prepare(
        SpecificationInterface $specification,
        EntityManager $entityManager,
        QueryBuilder $queryBuilder,
        AbstractQuery $query
    ) {
        $entityManager->createQueryBuilder()->willReturn($queryBuilder);

        $specification->getCondition($queryBuilder, $this->dqlAlias)->willReturn($this->expression);

        $queryBuilder->select($this->dqlAlias)->willreturn($queryBuilder);
        $queryBuilder->from(Argument::any(), $this->dqlAlias)->willReturn($queryBuilder);
        $queryBuilder->where($this->expression)->willReturn($queryBuilder);

        $queryBuilder->getQuery()->willReturn($query);

        $query->execute()->willReturn($this->result);
    }

}
