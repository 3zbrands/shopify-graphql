<?php

namespace Zzz\ShopifyGraphql\Responses\Products;

use Illuminate\Support\Arr;

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

    public function __get(string $name)
    {
        return $this->response['node'][$name] ?? null;
    }
}
