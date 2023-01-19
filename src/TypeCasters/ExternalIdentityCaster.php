<?php declare(strict_types=1);

namespace Somnambulist\Components\AttributeModel\TypeCasters;

use Somnambulist\Components\AttributeModel\Contracts\AttributeCasterInterface;
use Somnambulist\Components\Models\Types\Identity\ExternalIdentity;

final class ExternalIdentityCaster implements AttributeCasterInterface
{
    public function __construct(
        private string $providerAttribute = 'provider',
        private string $identityAttribute = 'identity',
        private bool $remove = true,
        private ?array $types = null
    ) {
    }

    public function types(): array
    {
        return $this->types ?? ['external_id', ExternalIdentity::class];
    }

    public function supports(string $type): bool
    {
        return in_array($type, $this->types());
    }

    public function cast(array &$attributes, mixed $attribute, string $type): void
    {
        if (!isset($attributes[$this->providerAttribute], $attributes[$this->identityAttribute])) {
            return;
        }

        $attributes[$attribute] = new ExternalIdentity($attributes[$this->providerAttribute], $attributes[$this->identityAttribute]);

        if ($this->remove) {
            unset($attributes[$this->providerAttribute], $attributes[$this->identityAttribute]);
        }
    }
}
