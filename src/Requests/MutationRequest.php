<?php

namespace Zzz\ShopifyGraphql\Requests;

use Saloon\Contracts\Body\HasBody;

abstract class MutationRequest extends Request implements HasBody
{
    abstract public function graphQuery(): string;

    public function defaultBody(): array
    {
        $requestGraphQuery = $this->graphQuery();

        $query = <<<QUERY
            mutation {
                $requestGraphQuery
            }
        QUERY;

        return [
            'query' => $query,
        ];
    }
}
