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
        $input = Str::of('');

        foreach ($this->attributes as $key => $value) {
            $input = $input->append("{$key}: \"{$value}\", ");
        }

        $input = $input->trim()->replaceLast(',', '')->start('{')->finish('}')->toString();

        return <<<QUERY
            productCreate(
                input: $input
            ) {
                product {
                    title
                    id
                }
                userErrors {
                    message
                }
            }
        QUERY;
    }
}
