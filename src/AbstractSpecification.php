<?php

namespace Rb\Specification\Doctrine;

abstract class AbstractSpecification implements SpecificationInterface
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
        $this->field    = $field;
        $this->dqlAlias = $dqlAlias;
    }

    /**
     * Create a formatted string for the given property prefixed with the DQL alias.
     *
     * @param string $dqlAlias
     *
     * @return string
     */
    protected function createPropertyWithAlias($dqlAlias)
    {
        return $this->createAliasedName($this->field, $dqlAlias);
    }

    /**
     * Create a formatted string where the value will be prefixed with DQL alias (if not already present).
     *
     * @param string $value
     * @param string $dqlAlias
     *
     * @return string
     */
    protected function createAliasedName($value, $dqlAlias)
    {
        if (strpos($value, '.') !== false) {
            return $value;
        }

        if (!empty($this->dqlAlias)) {
            $dqlAlias = $this->dqlAlias;
        }

        return sprintf('%s.%s', $dqlAlias, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($value)
    {
        return true;
    }
}
