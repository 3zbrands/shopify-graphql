<?php

namespace Zzz\ShopifyGraphql;

use Saloon\Http\Connector;
use Zzz\ShopifyGraphql\Resources\Cart;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Auth\HeaderAuthenticator;

class Shopify extends Connector
{
    private string $apiVersion;

    public function __construct(public readonly string $storeId, public string $apiToken)
    {
        $this->apiVersion = '2024-04';
    }

    protected function defaultAuth(): HeaderAuthenticator
    {
        return new HeaderAuthenticator($this->apiToken, 'X-Shopify-Storefront-Access-Token');
    }

    public function resolveBaseUrl(): string
    {
        return "https://{$this->storeId}.myshopify.com/api/{$this->apiVersion}/graphql.json";
    }

    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    public function cart(): Cart
    {
        return new Cart($this);
    }
}
