<?php

namespace Zzz\ShopifyGraphql\Trait;

use JsonException;
use Saloon\Http\Response;
use Zzz\ShopifyGraphql\Exceptions\GraphQlException;

trait ValidateGraphQlResponse
{
    /**
     * @throws GraphQlException
     * @throws JsonException
     */
    public function validate(Response $response, string $path = null): void
    {
        if ($response->json('errors')) {
            throw new GraphQlException('Error in GraphQL call. ' . json_encode($response->json('errors')));
        }

        if ($path && $response->json($path)) {
            throw new GraphQlException('Error in GraphQL call. ' . json_encode($response->json($path)));
        }
    }
}
