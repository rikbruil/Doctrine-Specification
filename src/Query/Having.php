<?php

namespace Rb\Specification\Doctrine\Query;

use Doctrine\ORM\QueryBuilder;
use Rb\Specification\Doctrine\Exception\InvalidArgumentException;
use Rb\Specification\Doctrine\SpecificationInterface;


/**
 * @author  Kyle Tucker <kyleatucker@gmail.com>
 */
class Having implements SpecificationInterface
{
    const HAVING     = 'having';
    const AND_HAVING = 'andHaving';
    const OR_HAVING  = 'orHaving';

    protected static $types = [self::HAVING, self::AND_HAVING, self::OR_HAVING];

    /** @var  string */
    protected $type;

    /** @var  SpecificationInterface */
    protected $specification;

    public function __construct(SpecificationInterface $specification, $type = self::AND_HAVING)
    {
        $this->specification = $specification;
        $this->setType($type);
    }

    public function modify(QueryBuilder $queryBuilder, $dqlAlias)
    {
        call_user_func_array(
            [$queryBuilder, $this->type],
            [
                $this->specification->modify($queryBuilder, $dqlAlias),
            ]
        );
    }

    public function isSatisfiedBy($value)
    {
        return true;
    }

    public function setType($type)
    {
        if (!in_array($type, self::$types)) {
            throw new InvalidArgumentException(sprintf(
                '"%s" is not a valid type! Valid types: %s',
                $type,
                implode(', ', self::$types)
            ));
        }

        $this->type = $type;
    }
}
