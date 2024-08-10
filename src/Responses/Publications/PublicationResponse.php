<?php

namespace Zzz\ShopifyGraphql\Responses\Publications;

use Illuminate\Support\Arr;

class PublicationResponse
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
        return $this->json('node.id');
    }

    public function name(): string
    {
        return $this->json('node.name');
    }

    public function __get(string $name)
    {
        return $this->response['node'][$name] ?? null;
    }
}
