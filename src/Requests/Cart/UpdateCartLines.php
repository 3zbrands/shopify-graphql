<?php

namespace Zzz\ShopifyGraphql\Requests\Cart;

use Exception;
use Illuminate\Support\Arr;
use Zzz\ShopifyGraphql\Requests\MutationRequest;
use Zzz\ShopifyGraphql\Requests\Models\CartLineRequest;
use Zzz\ShopifyGraphql\Requests\Cart\Traits\CommonQueryFields;

class UpdateCartLines extends MutationRequest
{
    use CommonQueryFields;

    /**
     * @throws Exception
     */
    public function __construct(protected $cartId, protected array|CartLineRequest $lines)
    {
        $this->lines = Arr::wrap($lines);

        foreach ($this->lines as $line) {
            if (! ($line instanceof CartLineRequest)) {
                throw new Exception('Lines attribute must be a array of CartLine classes');
            }
        }
    }

    public function graphQuery(): string
    {
        return <<<QUERY
            cartLinesUpdate(
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
              id: \"$line->gid\"
              quantity: $line->quantity,
              {$line->attributes()}
            }";
        }

        return $linesGraphQlString;
    }
}
