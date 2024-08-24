<?php

namespace Zzz\ShopifyGraphql\Requests\ShopifyFunctions;

use Exception;
use Zzz\ShopifyGraphql\Requests\QueryRequest;
use Zzz\ShopifyGraphql\Requests\Cart\Traits\CommonQueryFields;

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
            functions {
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
            }
        QUERY;
    }
}
