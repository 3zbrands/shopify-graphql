<?php

namespace Zzz\ShopifyGraphql\Requests\Cart;

use Exception;
use Zzz\ShopifyGraphql\Requests\MutationRequest;
use Zzz\ShopifyGraphql\Requests\Models\CartLineRequest;
use Zzz\ShopifyGraphql\Requests\Cart\Traits\CommonQueryFields;

class AddCartLines extends MutationRequest
{
    use CommonQueryFields;

    /**
     * @throws Exception
     */
    public function __construct(protected $cartId, protected array $lines)
    {
        foreach ($this->lines as $line) {
            if (! ($line instanceof CartLineRequest)) {
                throw new Exception('Lines attribute must be a array of CartLine classes');
            }
        }
    }

    public function graphQuery(): string
    {
        return <<<QUERY
            cartLinesAdd(
                cartId: "$this->cartId"
                lines: [
                    {$this->linesToGraphQl()}
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

    private function linesToGraphQl(): string
    {
        $linesGraphQlString = '';

        /** @var CartLineRequest $line */
        foreach ($this->lines as $line) {
            $linesGraphQlString .=
            "{
              merchandiseId: \"$line->gid\"
              quantity: $line->quantity,
              {$line->attributes()}
            }";
        }

        return $linesGraphQlString;
    }
}
