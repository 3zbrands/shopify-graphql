<?php

namespace Zzz\ShopifyGraphql\Resources;


use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Zzz\ShopifyGraphql\Trait\ValidateGraphQlResponse;
use Zzz\ShopifyGraphql\Requests\DiscountNodes\GetDiscountNodes;
use Zzz\ShopifyGraphql\Requests\DiscountNodes\GetDiscountNode;

class DiscountNodes
{
    use AlwaysThrowOnErrors;
    use ValidateGraphQlResponse;

    public function __construct(protected Connector $api)
    {
    }

    public function filter($titleFilter)
    {
        $response = $this->api->send(new GetDiscountNodes($titleFilter));

        $this->validate($response);

        return collect($response->json('data.discountNodes.edges'))
            ->map(fn ($edge) => [
                'id' => $edge['node']['id'],
                'title' => data_get($edge, 'node.discount.title', 'N/A'),
                'status' => data_get($edge, 'node.discount.status', 'N/A')
            ])
            ->filter(fn ($item) => $item['title'] !== 'N/A')
            ->toArray();
    }


    public function get($title)
    {
        $response = $this->api->send(new GetDiscountNode($title));

        $this->validate($response);

        return collect($response->json('data.codeDiscountNodes.edges'))
            ->toArray();
    }
}
