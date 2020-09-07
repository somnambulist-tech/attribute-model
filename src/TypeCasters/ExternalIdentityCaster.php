<?php declare(strict_types=1);

namespace Somnambulist\Components\AttributeModel\TypeCasters;

use Somnambulist\Components\AttributeModel\Contracts\AttributeCasterInterface;
use Somnambulist\Domain\Entities\Types\Identity\ExternalIdentity;

/**
 * Class ExternalIdentityCaster
 *
 * @package    Somnambulist\Components\AttributeModel\TypeCasters
 * @subpackage Somnambulist\Components\AttributeModel\TypeCasters\ExternalIdentityCaster
 */
final class ExternalIdentityCaster implements AttributeCasterInterface
{

    private string $providerAttribute;
    private string $identityAttribute;
    private bool $remove;
    private ?array $types;

    public function __construct(string $providerAttribute = 'provider', string $identityAttribute = 'identity', bool $remove = true, ?array $types = null)
    {
        $this->providerAttribute = $providerAttribute;
        $this->identityAttribute = $identityAttribute;
        $this->remove            = $remove;
        $this->types             = $types;
    }

    public function types(): array
    {
        return $this->types ?? ['external_id', ExternalIdentity::class];
    }

    public function supports(string $type): bool
    {
        return in_array($type, $this->types());
    }

    public function cast(array &$attributes, $attribute, string $type): void
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
