<?php declare(strict_types=1);

namespace Somnambulist\Components\AttributeModel\TypeCasters;

use Somnambulist\Components\AttributeModel\Contracts\AttributeCasterInterface;
use Somnambulist\Components\Models\Types\Money\Currency;
use Somnambulist\Components\Models\Types\Money\Money;

use function in_array;

final class MoneyCaster implements AttributeCasterInterface
{
    public function __construct(
        private string $amtAttribute = 'amount',
        private string $curAttribute = 'currency',
        private bool $remove = true
    ) {
    }

    public function types(): array
    {
        return ['money', Money::class];
    }

    public function supports(string $type): bool
    {
        return in_array($type, $this->types());
    }

    public function cast(array &$attributes, mixed $attribute, string $type): void
    {
        if (!isset($attributes[$this->amtAttribute], $attributes[$this->curAttribute])) {
            return;
        }

        $attributes[$attribute] = new Money(
            (float)$attributes[$this->amtAttribute],
            Currency::memberByKey($attributes[$this->curAttribute])
        );

        if ($this->remove) {
            unset($attributes[$this->amtAttribute], $attributes[$this->curAttribute]);
        }
    }
}
