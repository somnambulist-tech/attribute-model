<?php declare(strict_types=1);

namespace Somnambulist\Components\AttributeModel\Contracts;

interface AttributeCasterInterface
{
    /**
     * An array of the type names that this caster will respond to
     *
     * @return array
     */
    public function types(): array;

    public function supports(string $type): bool;

    /**
     * Cast attributes to a particular type / object resetting the attribute value
     *
     * @param array  $attributes
     * @param mixed  $attribute
     * @param string $type
     */
    public function cast(array &$attributes, mixed $attribute, string $type): void;
}
