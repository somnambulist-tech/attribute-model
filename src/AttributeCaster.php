<?php declare(strict_types=1);

namespace Somnambulist\Components\AttributeModel;

use Somnambulist\Components\AttributeModel\Contracts\AttributeCasterInterface as CasterInterface;
use Somnambulist\Components\AttributeModel\Exceptions\AttributeCasterException;
use function array_key_exists;

/**
 * Class AttributeCaster
 *
 * @package    Somnambulist\Components\AttributeModel
 * @subpackage Somnambulist\Components\AttributeModel\AttributeCaster
 */
final class AttributeCaster
{

    private array $casters = [];

    public function __construct(iterable $casters = [])
    {
        $this->addAll($casters);
    }

    public function addAll(iterable $casters): void
    {
        foreach ($casters as $caster) {
            $this->add($caster);
        }
    }

    /**
     * @param CasterInterface $caster
     * @param array|null      $types  If provided, registers these types instead of the casters declared types
     */
    public function add(CasterInterface $caster, ?array $types = null): void
    {
        $types = $types ?? $caster->types();

        foreach ($types as $type) {
            $this->casters[$type] = $caster;
        }
    }

    public function cast(array $attributes, array $casts): array
    {
        if (count($attributes) > 0) {
            foreach ($casts as $attribute => $type) {
                $this->for($type)->cast($attributes, $attribute, $type);
            }
        }

        return $attributes;
    }

    public function has(string $type): bool
    {
        return array_key_exists($type, $this->casters);
    }

    public function for(string $type): CasterInterface
    {
        if (!$this->has($type)) {
            throw AttributeCasterException::missingTypeFor($type);
        }

        return $this->casters[$type];
    }

    /**
     * Extends the bound AttributeCaster for $type to all types in $to
     *
     * @param string $type
     * @param array  $to
     *
     * @throws AttributeCasterException
     */
    public function extend(string $type, array $to): void
    {
        $this->add($this->for($type), $to);
    }
}
