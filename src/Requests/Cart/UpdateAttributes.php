<?php

namespace Zzz\ShopifyGraphql\Requests\Cart;

use Exception;
use Zzz\ShopifyGraphql\Requests\MutationRequest;
use Zzz\ShopifyGraphql\Requests\Cart\Traits\CommonQueryFields;

class UpdateAttributes extends MutationRequest
{
    use CommonQueryFields;

    /**
     * @throws Exception
     */
    public function __construct(protected $cartId, protected array $attributes)
    {
        //
    }

    public function graphQuery(): string
    {
        return <<<QUERY
            cartAttributesUpdate(
                cartId: "{$this->cartId}"
                attributes: [{$this->formatAttributes()}]
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

    private function formatAttributes()
    {
        return collect($this->attributes)->map(function ($value, $key) {
            $escapedValue = addslashes($value);

            return "{key: \"$key\", value: \"$escapedValue\"}";
        })->values()->implode(',');
    }
}
