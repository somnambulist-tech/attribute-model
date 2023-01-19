<?php declare(strict_types=1);

namespace Somnambulist\Components\AttributeModel\TypeCasters;

use Somnambulist\Components\AttributeModel\Contracts\AttributeCasterInterface;
use Somnambulist\Components\Collection\MutableCollection as Collection;

use function array_key_exists;
use function in_array;
use function is_array;
use function json_decode;

use const JSON_THROW_ON_ERROR;

/**
 * Cast a JSON string to a collection object.
 */
final class JsonCollectionCaster implements AttributeCasterInterface
{
    public function __construct(private array $types = ['json', 'json_collection'])
    {
    }

    public function types(): array
    {
        return $this->types;
    }

    public function supports(string $type): bool
    {
        return in_array($type, $this->types());
    }

    public function cast(array &$attributes, mixed $attribute, string $type): void
    {
        $data = [];

        if (array_key_exists($attribute, $attributes)) {
            if (is_string($attributes[$attribute])) {
                $data = json_decode($attributes[$attribute] ?? '{}', true, 512, JSON_THROW_ON_ERROR);
            } elseif (is_array($attributes[$attribute])) {
                $data = $attributes[$attribute];
            }
        }

        $attributes[$attribute] = new Collection($data);
    }
}
