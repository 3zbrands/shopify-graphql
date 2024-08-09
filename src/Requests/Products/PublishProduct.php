<?php

namespace Zzz\ShopifyGraphql\Requests\Products;

use Zzz\ShopifyGraphql\Requests\MutationRequest;
use Zzz\ShopifyGraphql\Requests\Cart\Traits\CommonQueryFields;

class PublishProduct extends MutationRequest
{
    use CommonQueryFields;

    public function __construct( protected string $productId, protected array $attributes = [])
    {
        //
    }

    public function graphQuery(): string
    {
        $input = $this->convertArrayWithKeysToGraphQlSyntax($this->attributes);

        return <<<QUERY
            publishablePublish(
                id: "$this->productId",
                input: $input
            ) {
                userErrors {
                    message
                }
            }
        QUERY;
    }
}
