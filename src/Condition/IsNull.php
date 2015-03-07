<?php

namespace Rb\Doctrine\Specification\Condition;

use Doctrine\ORM\QueryBuilder;

class IsNull implements ModifierInterface
{
    /**
     * @var string
     */
    protected $field;

    /**
     * @var string|null
     */
    protected $dqlAlias;

    /**
     * @param string      $field
     * @param string|null $dqlAlias
     */
    public function __construct($field, $dqlAlias = null)
    {
        $this->field = $field;
        $this->dqlAlias = $dqlAlias;
    }

    /**
     * Return a string expression which can be used as condition (in WHERE-clause)
     * @param  QueryBuilder $queryBuilder
     * @param  string       $dqlAlias
     * @return string
     */
    public function getCondition(QueryBuilder $queryBuilder, $dqlAlias)
    {
        if ($this->dqlAlias) {
            $dqlAlias = $this->dqlAlias;
        }

        $property = sprintf('%s.%s', $dqlAlias, $this->field);

        return (string) $queryBuilder->expr()->isNull($property);
    }
}
