<?php

namespace Zzz\ShopifyGraphql\Requests\Cart;

use Exception;
use Zzz\ShopifyGraphql\Requests\QueryRequest;
use Zzz\ShopifyGraphql\Requests\Cart\Traits\CommonQueryFields;

class GetCart extends QueryRequest
{
    use CommonQueryFields;

    public function __construct(protected string $cartId)
    {
        //
    }

    public function graphQuery(): string
    {
        return <<<QUERY
            cart(id: "$this->cartId") {
                {$this->commonCartFields()}
            }
        QUERY;
    }
}
