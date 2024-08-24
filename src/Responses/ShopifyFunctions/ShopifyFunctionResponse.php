<?php

namespace Zzz\ShopifyGraphql\Responses\ShopifyFunctions;

use Illuminate\Support\Arr;

class ShopifyFunctionResponse
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

    public function cartTransformFunctionId(): string|null
    {
        return $this->json('shopifyFunctions.edges.node.id');
    }

    public function __get(string $name)
    {
        return $this->response['node'][$name] ?? null;
    }
}
