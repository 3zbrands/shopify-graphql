<?php

namespace Zzz\ShopifyGraphql\Requests\Functions;

use Exception;
use Zzz\ShopifyGraphql\Requests\Cart\Traits\CommonQueryFields;
use Zzz\ShopifyGraphql\Requests\MutationRequest;

class EnableShopifyFunction extends MutationRequest
{
    use CommonQueryFields;

    public function __construct(protected string $functionId)
    {
        //
    }

    public function graphQuery(): string
    {
        return <<<QUERY
          cartTransformCreate(functionId: "8de29a31-c027-4fe7-b14d-9445d26872c3") {
            cartTransform {
              id
            }
            userErrors {
              message
            }
          }
        QUERY;
    }
}
