<?php

namespace spec\Rb\Specification\Doctrine\Result;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;
use Rb\Specification\Doctrine\Result\ModifierInterface;
use PhpSpec\ObjectBehavior;

class AsArraySpec extends ObjectBehavior
{
    public function it_is_a_result_modifier()
    {
        $this->shouldHaveType(ModifierInterface::class);
    }

    public function it_sets_hydration_mode_to_array(AbstractQuery $query)
    {
        $query->setHydrationMode(Query::HYDRATE_ARRAY)->shouldBeCalled();

        $this->modify($query);
    }
}
