<?php

namespace Rb\Specification\Doctrine\Query;

use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\AbstractSpecification;
use Rb\Specification\Doctrine\Helper\TypeCheckerTrait;

/**
 * @author  Kyle Tucker <kyleatucker@gmail.com>
 */
class GroupBy extends AbstractSpecification
{
    use TypeCheckerTrait;

    const GROUP_BY     = 'groupBy';
    const ADD_GROUP_BY = 'addGroupBy';

    /** @var string[] */
    protected $validTypes = [self::GROUP_BY, self::ADD_GROUP_BY];

    /**
     * Constructor.
     *
     * @param string      $field
     * @param string      $type
     * @param string|null $dqlAlias
     */
    public function __construct($field, $type = self::ADD_GROUP_BY, $dqlAlias = null)
    {
        $this->setType($type);
        parent::__construct($field, $dqlAlias);
    }

    /**
     * {@inheritdoc}
     */
    public function modify(QueryBuilder $queryBuilder, $dqlAlias)
    {
        call_user_func_array(
            [$queryBuilder, $this->type],
            [
                $this->createPropertyWithAlias($dqlAlias),
            ]
        );
    }
}
