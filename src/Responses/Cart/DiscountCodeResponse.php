<?php

namespace Zzz\ShopifyGraphql\Responses\Cart;

class DiscountCodeResponse
{
    public function __construct(public readonly string $code, public readonly bool $applicable)
    {
        //
    }

    public function code(): string
    {
        return $this->code;
    }

    public function isApplicable(): bool
    {
        return $this->applicable;
    }
}
