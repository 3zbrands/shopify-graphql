<?php

namespace Zzz\ShopifyGraphql\Resources;

use Throwable;
use JsonException;
use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
// use Zzz\ShopifyGraphql\Requests\Functions\EnableShopifyFunction;
use Zzz\ShopifyGraphql\Requests\Functions\GetAllShopifyFunctions;
// use Zzz\ShopifyGraphql\Requests\Functions\GetFunctionByHandleAndDeveloperName;
use Zzz\ShopifyGraphql\Responses\Functions\FunctionResponse;
use Zzz\ShopifyGraphql\Responses\Functions\FunctionsResponse;
use Saloon\Exceptions\Request\RequestException;
use Zzz\ShopifyGraphql\Exceptions\GraphQlException;
use Saloon\Exceptions\Request\FatalRequestException;
use Zzz\ShopifyGraphql\Trait\ValidateGraphQlResponse;

class Functions
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
    // public function create(array $attributes = []): ProductResponse
    // {
    //     $response = $this->api->send(new CreateProduct($attributes));

    //     $this->validate($response);

    //     return new ProductResponse($response->json('data.productCreate.product'));
    // }

    /**
     * @throws FatalRequestException
     * @throws RequestException
     * @throws GraphQlException
     * @throws JsonException
     */
    public function getAllShopifyFunctions(): FunctionsResponse
    {
        $response = $this->api->send(new GetAllShopifyFunctions());

        $this->validate($response);

        return new FunctionsResponse($response->json('data.shopifyFunctions'));
    }

    /**
     * @throws FatalRequestException
     * @throws RequestException
     * @throws GraphQlException
     * @throws JsonException
     */
      // public function getByHandleAndDeveloperName(string $functionHandle, string $developerName): ShopifyFunctionResponse
      // {
      //     $response = $this->api->send(new GetProductByHandle($productHandle));

      //     $this->validate($response);

      //     return new ProductResponse($response->json('data.productByHandle') ?? []);
      // }
}
