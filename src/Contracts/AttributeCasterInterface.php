<?php declare(strict_types=1);

namespace Somnambulist\Components\AttributeModel\Contracts;

/**
 * Interface AttributeCasterInterface
 *
 * @package    Somnambulist\Components\AttributeModel\Contracts
 * @subpackage Somnambulist\Components\AttributeModel\Contracts\AttributeCasterInterface
 */
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
     * @param array            $attributes
     * @param string|int|float $attribute
     * @param string           $type
     */
    public function cast(array &$attributes, $attribute, string $type): void;
}
