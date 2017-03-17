<?php

namespace Rb\Specification\Doctrine\Query;

use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\Helper\TypeCheckerTrait;
use Rb\Specification\Doctrine\SpecificationInterface;

/**
 * Select will modify the query-builder so you can specify SELECT-statements.
 */
class Select implements SpecificationInterface
{
    use TypeCheckerTrait;

    const SELECT     = 'select';
    const ADD_SELECT = 'addSelect';

    protected $validTypes = [self::SELECT, self::ADD_SELECT];

    /**
     * @var string|array
     */
    protected $select;

    /**
     * @param string|array $select
     * @param string       $type
     */
    public function __construct($select, $type = self::ADD_SELECT)
    {
        $this->setType($type);
        $this->select = $select;
    }

    /**
     * {@inheritdoc}
     */
    public function modify(QueryBuilder $queryBuilder, $dqlAlias)
    {
        call_user_func_array([$queryBuilder, $this->type], [$this->select]);
    }

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($value)
    {
        return true;
    }
}
