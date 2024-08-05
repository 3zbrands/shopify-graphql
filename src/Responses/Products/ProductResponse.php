<?php

namespace Zzz\ShopifyGraphql\Responses\Products;

use Money\Money;
use Money\Currency;
use Saloon\Http\Response;
use Illuminate\Support\Arr;
use Zzz\ShopifyGraphql\Shopify;
use Illuminate\Support\Collection;
use Zzz\ShopifyGraphql\Trait\ConvertCurrencyType;
use Zzz\ShopifyGraphql\Responses\Cart\CartLinesResponse;
use Zzz\ShopifyGraphql\Responses\Cart\DiscountCodeResponse;
use Zzz\ShopifyGraphql\Responses\Cart\DiscountAllocationResponse;

class ProductResponse
{
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

    public function firstVariantId(): string|null
    {
        return $this->json('variants.edges.0.node.id');
    }
}
