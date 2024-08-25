<?php

namespace Zzz\ShopifyGraphql\Requests\Functions;

use Exception;
use Zzz\ShopifyGraphql\Requests\Cart\Traits\CommonQueryFields;
use Zzz\ShopifyGraphql\Requests\QueryRequest;

class GetAllShopifyFunctions extends QueryRequest
{
    use CommonQueryFields;

    public function __construct()
    {
        //
    }

    public function graphQuery(): string
    {
        return <<<QUERY
              shopifyFunctions(first: 250, apiType: "cart_transform") {
                edges {
                  node {
                    id
                    title
                    app {
                      developerName
                      id
                    }
                    apiType
                  }
                }
              }
        QUERY;
    }
}
