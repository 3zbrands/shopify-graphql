<?php

namespace Zzz\ShopifyGraphql\Requests\Products;

use Exception;
use Zzz\ShopifyGraphql\Requests\QueryRequest;
use Zzz\ShopifyGraphql\Requests\Cart\Traits\CommonQueryFields;

class GetProductByHandle extends QueryRequest
{
    use CommonQueryFields;

    public function __construct(protected string $productHandle)
    {
        //
    }

    public function graphQuery(): string
    {
        return <<<QUERY
            productByHandle(handle: "$this->productHandle") {
                id
                variants(first: 1) {
                  edges {
                    node {
                      id
                    }
                  }
                }
            }
        QUERY;
    }
}
