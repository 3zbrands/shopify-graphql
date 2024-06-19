<?php

namespace Zzz\ShopifyGraphql\Responses\Cart;

use Money\Money;
use Money\Currency;
use Saloon\Http\Response;
use Illuminate\Support\Arr;
use Zzz\ShopifyGraphql\Shopify;
use Illuminate\Support\Collection;
use Zzz\ShopifyGraphql\Trait\ConvertCurrencyType;

class CartResponse
{
    use ConvertCurrencyType;

    public function __construct(readonly public array $response)
    {
        //
    }

    public function json(string $key = '')
    {
        return Arr::get($this->response, $key);
    }

    public function id(): string
    {
        return $this->json('id');
    }

    public function lines(): CartLinesResponse
    {
        return new CartLinesResponse($this->json('lines.edges'));
    }

    public function totalQuantity(): int
    {
        return $this->json('totalQuantity');
    }

    public function discountCodes(): Collection
    {
        return collect($this->json('discountCodes'))
            ->map(function (array $discountCode) {
                return new DiscountCodeResponse($discountCode['code'], $discountCode['applicable']);
            });
    }

    public function subtotalAmount(): Money
    {
        return new Money(
            $this->dollarsAsFloatToCent($this->json('cost.subtotalAmount.amount')),
            new Currency($this->json('cost.subtotalAmount.currencyCode'))
        );
    }

    public function totalTaxAmount(): Money
    {
        if (is_null($this->json('cost.totalTaxAmount'))) {
            return new Money(0, $this->getCurrency());
        }

        return new Money(
            $this->dollarsAsFloatToCent($this->json('cost.totalTaxAmount.amount')),
            new Currency($this->json('cost.totalTaxAmount.currencyCode'))
        );
    }

    public function totalAmount(): Money
    {
        return new Money(
            $this->dollarsAsFloatToCent($this->json('cost.totalAmount.amount')),
            new Currency($this->json('cost.totalAmount.currencyCode'))
        );
    }

    public function getCurrency(): Currency
    {
        return new Currency($this->json('cost.totalAmount.currencyCode'));
    }

    public function note(): string
    {
        return $this->json('note');
    }

    public function checkoutUrl(): string
    {
        return $this->json('checkoutUrl');
    }
}
