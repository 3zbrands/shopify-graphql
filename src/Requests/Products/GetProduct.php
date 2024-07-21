<?php

namespace Zzz\ShopifyGraphql\Requests\Products;

use Exception;
use Zzz\ShopifyGraphql\Requests\QueryRequest;
use Zzz\ShopifyGraphql\Requests\Cart\Traits\CommonQueryFields;

class GetProduct extends QueryRequest
{
    use CommonQueryFields;

    public function __construct(protected string $productId)
    {
        //
    }

    public function graphQuery(): string
    {
        return <<<QUERY
            product(id: "$this->productId") {
                id
                title
                handle
                status
                description
                descriptionHtml
                onlineStoreUrl
            }
        QUERY;
    }
}
