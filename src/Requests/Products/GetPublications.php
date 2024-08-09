<?php

namespace Zzz\ShopifyGraphql\Requests\Products;

use Zzz\ShopifyGraphql\Requests\QueryRequest;
use Zzz\ShopifyGraphql\Requests\Cart\Traits\CommonQueryFields;

class GetPublications extends QueryRequest
{
    use CommonQueryFields;

    public function __construct()
    {
        //
    }

    public function graphQuery(): string
    {
        return <<<QUERY
            publications(first: 250) {
              edges {
                node {
                  id
                  name
                }
              }
            }
        QUERY;
    }
}
