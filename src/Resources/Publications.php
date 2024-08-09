<?php

namespace Zzz\ShopifyGraphql\Resources;

use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Zzz\ShopifyGraphql\Requests\Products\GetPublications;
use Zzz\ShopifyGraphql\Requests\Products\PublishProduct;
use Zzz\ShopifyGraphql\Responses\Publications\PublicationsResponse;
use Zzz\ShopifyGraphql\Trait\ValidateGraphQlResponse;

class Publications
{
    use AlwaysThrowOnErrors;
    use ValidateGraphQlResponse;

    public function __construct(protected Connector $api)
    {
    }

    public function getPublications(): PublicationsResponse
    {
        $response = $this->api->send(new GetPublications());

        $this->validate($response);

        return new PublicationsResponse($response->json('data.publications') ?? []);
    }

    public function publishProduct(string $productId, string $publicationId): void
    {
        $response = $this->api->send(
            new PublishProduct(
                productId: $productId,
                attributes: [
                    'publicationId' => $publicationId
                ]
            )
        );

        $this->validate($response);
    }
}
