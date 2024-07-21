<?php

namespace Zzz\ShopifyGraphql;

use Exception;
use Saloon\Http\Connector;
use Zzz\ShopifyGraphql\Resources\Cart;
use Zzz\ShopifyGraphql\Enums\ApiVersion;
use Saloon\Http\Auth\HeaderAuthenticator;
use Zzz\ShopifyGraphql\Resources\Products;

class Shopify extends Connector
{
    private string $baseUrl = '';
    private HeaderAuthenticator|null $headerAuthenticator = null;

    public function __construct(
        public readonly string $storeId,
        public readonly string $adminAccessToken,
        public readonly string $storefrontAccessToken,
        public readonly ApiVersion $apiVersion
    )
    {
        //
    }

    protected function defaultAuth(): HeaderAuthenticator
    {
        throw_if(blank($this->headerAuthenticator), Exception::class, 'Must specify which API to connect to.');

        return $this->headerAuthenticator;
    }

    public function resolveBaseUrl(): string
    {
        throw_if(blank($this->baseUrl), Exception::class, 'Must specify which API to connect to.');

        return $this->baseUrl;
    }

    protected function connectToStorefront(): static
    {
        $this->baseUrl = "https://{$this->storeId}.myshopify.com/api/{$this->apiVersion->value}/graphql.json";
        $this->headerAuthenticator = new HeaderAuthenticator($this->storefrontAccessToken, 'X-Shopify-Storefront-Access-Token');

        return $this;
    }

    protected function connectToAdmin(): static
    {
        $this->baseUrl = "https://{$this->storeId}.myshopify.com/admin/api/{$this->apiVersion->value}/graphql.json";
        $this->headerAuthenticator = new HeaderAuthenticator($this->adminAccessToken, 'X-Shopify-Access-Token');

        return $this;
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
        $this->connectToStorefront();

        return new Cart($this);
    }

    public function products(): Products
    {
        $this->connectToAdmin();

        return new Products($this);
    }
}
