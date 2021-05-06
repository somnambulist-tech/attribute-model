<?php declare(strict_types=1);

namespace Somnambulist\Components\AttributeModel\TypeCasters;

use Somnambulist\Collection\MutableCollection as Collection;
use Somnambulist\Components\AttributeModel\Contracts\AttributeCasterInterface;
use function in_array;
use function is_array;
use function json_decode;
use const JSON_THROW_ON_ERROR;

/**
 * Class JsonCollectionCaster
 *
 * Cast a JSON string to a collection object.
 *
 * @package    Somnambulist\Components\AttributeModel\TypeCasters
 * @subpackage Somnambulist\Components\AttributeModel\TypeCasters\JsonCollectionCaster
 */
final class JsonCollectionCaster implements AttributeCasterInterface
{

    private array $types;

    public function __construct(array $types = ['json', 'json_array', 'json_collection'])
    {
        $this->types = $types;
    }

    public function types(): array
    {
        return $this->types;
    }

    public function supports(string $type): bool
    {
        return in_array($type, $this->types());
    }

    public function cast(array &$attributes, $attribute, string $type): void
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
