<?php

namespace Zzz\ShopifyGraphql\Requests\Functions;

use Exception;
use Zzz\ShopifyGraphql\Trait\HasEdges;
use Zzz\ShopifyGraphql\Requests\QueryFunctionRequest;
use Zzz\ShopifyGraphql\Requests\Cart\Traits\CommonQueryFields;

class GetAllShopifyFunctions extends QueryFunctionRequest
{
    use CommonQueryFields;
    use HasEdges;

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
