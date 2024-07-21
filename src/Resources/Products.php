<?php

namespace Zzz\ShopifyGraphql\Resources;

use Throwable;
use JsonException;
use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Saloon\Exceptions\Request\RequestException;
use Zzz\ShopifyGraphql\Requests\Products\GetProduct;
use Zzz\ShopifyGraphql\Exceptions\GraphQlException;
use Saloon\Exceptions\Request\FatalRequestException;
use Zzz\ShopifyGraphql\Trait\ValidateGraphQlResponse;
use Zzz\ShopifyGraphql\Requests\Products\CreateProduct;
use Zzz\ShopifyGraphql\Responses\Products\ProductResponse;
use Zzz\ShopifyGraphql\Responses\Cart\CartResponse as CartResponse;

class Products
{
    use AlwaysThrowOnErrors;
    use ValidateGraphQlResponse;

    public function __construct(protected Connector $api)
    {
    }

    /**
     * @throws Throwable
     * @throws FatalRequestException
     * @throws RequestException
     * @throws JsonException
     * @throws GraphQlException
     */
    public function create(array $attributes = []): ProductResponse
    {
        $response = $this->api->send(new CreateProduct($attributes));

        $this->validate($response);

        return new ProductResponse($response->json('data.productCreate.product'));
    }

    /**
     * @throws FatalRequestException
     * @throws RequestException
     * @throws GraphQlException
     * @throws JsonException
     */
    public function get(string $productId): ProductResponse
    {
        $response = $this->api->send(new GetProduct($productId));

        $this->validate($response);

        return new ProductResponse($response->json('data.product'));
    }
}
