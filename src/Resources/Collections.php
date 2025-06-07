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
use Zzz\ShopifyGraphql\Requests\Collections\GetCollection;
use Zzz\ShopifyGraphql\Requests\Collections\GetCollections;
use Zzz\ShopifyGraphql\Responses\Collections\CollectionResponse;
use Zzz\ShopifyGraphql\Responses\Collections\CollectionsResponse;
use Zzz\ShopifyGraphql\Responses\Cart\CartResponse as CartResponse;

class Collections
{
    use AlwaysThrowOnErrors;
    use ValidateGraphQlResponse;

    public function __construct(protected Connector $api)
    {
    }

    /**
     * @throws FatalRequestException
     * @throws RequestException
     * @throws GraphQlException
     * @throws JsonException
     */
    public function get(
        string|null $collectionId = null,
        int|null $first = 250,
        int|null $last = null,
        bool $reverse = true,
        string|null $after = null,
        string|null $before = null,
        string|null $searchQuery = null,
    )
    {
        if ($collectionId) {
            $response = $this->api->send(new GetCollection(
                $collectionId,
                $first,
                $last,
                $reverse,
                $after,
                $before,
                $searchQuery
            ));

            $this->validate($response);

            return new CollectionResponse($response->json('data.collection') ?? []);
        }

        $response = $this->api->send(new GetCollections(
            $first,
            $last,
            $reverse,
            $after,
            $before,
            $searchQuery,
        ));

        $this->validate($response);

        return new CollectionsResponse($response->json('data.collections'));
    }

    public function all()
    {
        $collections = $this->get(first: 250);
        $cursor = collect($collections->collections['edges'] ?? [])->last()['cursor'] ?? null;
        $allCollections = $collections->all;

        while (! is_null($cursor)) {
            $collections = $this->get(first: 250, after: $cursor);
            $allCollections = $allCollections->merge($collections->all);
            $cursor = collect($collections->collections['edges'] ?? [])->last()['cursor'] ?? null;
        }

        $allCollections = $allCollections->map(function (CollectionResponse $collection) {
            return $collection->edge['node'] ?? [];
        });

        return new CollectionsResponse(['edges' => $allCollections]);
    }
}
