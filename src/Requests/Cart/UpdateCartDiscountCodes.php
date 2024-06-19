<?php

namespace Zzz\ShopifyGraphql\Requests\Cart;

use Exception;
use Zzz\ShopifyGraphql\Requests\MutationRequest;
use Zzz\ShopifyGraphql\Requests\Cart\Traits\CommonQueryFields;

class UpdateCartDiscountCodes extends MutationRequest
{
    use CommonQueryFields;

    /**
     * @throws Exception
     */
    public function __construct(protected $cartId, protected array $discountCodes)
    {
        foreach ($this->discountCodes as $discountCode) {
            if (! is_string($discountCode)) {
                throw new Exception('Discount codes must be an array of strings.');
            }
        }
    }

    public function graphQuery(): string
    {
        return <<<QUERY
            cartDiscountCodesUpdate(
                cartId: "$this->cartId"
                discountCodes: [
                    "{$this->discountCodesToGraphQl()}"
                ]
            ) {
                cart {
                    {$this->commonCartFields()}
                }
                userErrors {
                    {$this->commonUserErrorsFields()}
                }
            }
        QUERY;
    }

    private function discountCodesToGraphQl(): string
    {
        return implode(', ', $this->discountCodes);
    }
}
