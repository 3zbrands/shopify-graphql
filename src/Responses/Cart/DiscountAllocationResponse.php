<?php

namespace Zzz\ShopifyGraphql\Responses\Cart;

use Money\Money;
use Money\Currency;

class DiscountAllocationResponse
{
    public function __construct(public readonly int $amount, public readonly string $currencyCode)
    {
        //
    }

    public function toMoney(): Money
    {
        return new Money($this->amount, new Currency($this->currencyCode));
    }
}
