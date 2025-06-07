<?php

namespace Zzz\ShopifyGraphql\Responses\Collections;

use Illuminate\Contracts\Support\Arrayable;
use Zzz\ShopifyGraphql\Responses\Products\ProductsResponse;

class CollectionResponse implements Arrayable
{
    public function __construct(public readonly array $edge = [])
    {
        //
    }

    public function __get(string $name)
    {
        return $this->edge[$name] ?? null;
    }

    public function products()
    {
        return new ProductsResponse($this->edge['products'] ?? []);
    }

    public function toArray()
    {
        return $this->edge;
    }
}
