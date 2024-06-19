<?php

namespace Zzz\ShopifyGraphql\Requests\Cart;

use Exception;
use Zzz\ShopifyGraphql\Requests\MutationRequest;
use Zzz\ShopifyGraphql\Requests\Models\CartLineRequest;
use Zzz\ShopifyGraphql\Requests\Cart\Traits\CommonQueryFields;

class CreateCart extends MutationRequest
{
    use CommonQueryFields;

    public function __construct(protected array $lines = [])
    {
        //
    }

    public function graphQuery(): string
    {
        $input = empty($this->lines) ? '{}' : $this->input($this->lines);

        return <<<QUERY
            cartCreate(
                input: $input
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

    private function input(array $lines): string
    {
        $linesGraphQlString = '';

        foreach ($lines as $line) {
            if (! ($line instanceof CartLineRequest)) {
                throw new Exception('Lines attribute must be a array of CartLine classes');
            }

            $linesGraphQlString .= <<<GRAPHQL
            {
                merchandiseId: "{$line->gid}",
                quantity: $line->quantity,
                {$line->attributes()}
            }
        GRAPHQL;
        }

        return <<<INPUT
        {
          lines: [
            $linesGraphQlString
          ],
        }
        INPUT;
    }
}
