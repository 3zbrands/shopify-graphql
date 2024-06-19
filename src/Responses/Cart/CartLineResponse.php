<?php

namespace Zzz\ShopifyGraphql\Responses\Cart;

use Money\Money;
use Money\Currency;
use Illuminate\Support\Collection;
use Zzz\ShopifyGraphql\Trait\ConvertCurrencyType;

class CartLineResponse
{
    use ConvertCurrencyType;

    public readonly string $id;
    public readonly int $quantity;
    public readonly array $attributes;

    public function __construct(protected array $edge = [])
    {
        $this->id = $this->edge['node']['id'];
        $this->quantity = $this->edge['node']['quantity'];
        $this->attributes = $this->edge['node']['attributes'];
    }

    public function node(): array
    {
        return $this->edge['node'];
    }

    public function id(): string
    {
        return $this->node()['id'];
    }

    public function attributes(): Collection
    {
        return new Collection($this->node()['attributes'] ?? []);
    }

    public function attribute(string $key): mixed
    {
        return $this->attributes()->firstWhere(fn (array $attribute) => $attribute['key'] === $key)['value'] ?? null;
    }

    public function hasAttribute(string $key): bool
    {
        return $this->attributes()->contains(fn (array $attribute) => $attribute['key'] === $key);
    }

    public function title(): string
    {
        return $this->node()['merchandise']['title'];
    }

    public function quantity(): int
    {
        return $this->node()['quantity'];
    }

    public function subtotal(): Money
    {
        return new Money(
            $this->dollarsAsFloatToCent($this->node()['cost']['subtotalAmount']['amount']),
            new Currency($this->node()['cost']['subtotalAmount']['currencyCode'])
        );
    }

    public function total(): Money
    {
        return new Money(
            $this->dollarsAsFloatToCent($this->node()['cost']['totalAmount']['amount']),
            new Currency($this->node()['cost']['totalAmount']['currencyCode'])
        );
    }

    public function currency(): Currency
    {
        return $this->total()->getCurrency();
    }

    public function totalDiscountAmount(): Money
    {
        return collect($this->node()['discountAllocations'])
            ->reduce(function (Money $carry, $discountAllocation) {
                $discount = new Money(
                    $this->dollarsAsFloatToCent($discountAllocation['discountedAmount']['amount']),
                    new Currency($discountAllocation['discountedAmount']['currencyCode'])
                );

                return $carry->add($discount);
            }, new Money(0, $this->currency()));
    }

    public function amountPerQuantity(): Money
    {
        return new Money(
            $this->dollarsAsFloatToCent($this->node()['cost']['amountPerQuantity']['amount']),
            new Currency($this->node()['cost']['amountPerQuantity']['currencyCode'])
        );
    }

    public function productVariantId(): string
    {
        return $this->node()['merchandise']['id'] ?? '';
    }
}
