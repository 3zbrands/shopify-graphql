<?php

namespace Zzz\ShopifyGraphql\Trait;

use JsonException;
use Saloon\Http\Response;
use Zzz\ShopifyGraphql\Exceptions\GraphQlException;
use Zzz\ShopifyGraphql\Exceptions\SpecificCartDoesNotExistException;

trait ValidateGraphQlResponse
{
    /**
     * @throws GraphQlException
     * @throws JsonException
     * @throws SpecificCartDoesNotExistException
     */
    public function validate(Response $response, string $path = null): void
    {
        if ($response->json('errors')) {
            throw new GraphQlException('Error in GraphQL call. ' . json_encode($response->json('errors')));
        }

        if ($path && $response->json($path)) {
            if (($response->json($path)[0]['message'] ?? null) === 'The specified cart does not exist.') {
                throw new SpecificCartDoesNotExistException($response->json($path)[0]['message']);
            }

            throw new GraphQlException('Error in GraphQL call. ' . json_encode($response->json($path)));
        }
    }
}
