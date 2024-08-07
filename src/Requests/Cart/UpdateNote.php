<?php

namespace Zzz\ShopifyGraphql\Requests\Cart;

use Exception;
use Zzz\ShopifyGraphql\Requests\MutationRequest;
use Zzz\ShopifyGraphql\Requests\Cart\Traits\CommonQueryFields;

class UpdateNote extends MutationRequest
{
    use CommonQueryFields;

    /**
     * @throws Exception
     */
    public function __construct(protected $cartId, protected string $note)
    {
        //
    }

    public function graphQuery(): string
    {
        return <<<QUERY
            cartNoteUpdate(
                cartId: "{$this->cartId}"
                note: "{$this->note}"
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
}
