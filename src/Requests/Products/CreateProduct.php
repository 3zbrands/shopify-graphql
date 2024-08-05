<?php

namespace Zzz\ShopifyGraphql\Requests\Products;

use Illuminate\Support\Str;
use Zzz\ShopifyGraphql\Requests\MutationRequest;

class CreateProduct extends MutationRequest
{
    public function __construct(protected array $attributes = [])
    {
        //
    }

    public function graphQuery(): string
    {
        $input = $this->convertArrayWithKeysToGraphQlSyntax($this->attributes);

        return <<<QUERY
            productCreate(
                input: $input
            ) {
                product {
                    title
                    id
                    variants(first: 1) {
                      edges {
                        node {
                          id
                        }
                      }
                    }
                }
                userErrors {
                    message
                }
            }
        QUERY;
    }
}
