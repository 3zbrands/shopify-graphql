<?php

namespace Zzz\ShopifyGraphql\Requests;

abstract class QueryRequest extends Request
{
    abstract public function graphQuery(): string;

    public function defaultBody(): array
    {
        $requestGraphQuery = $this->graphQuery();

        $query = <<<QUERY
            query {
                $requestGraphQuery
            }
        QUERY;

        return [
            'query' => $query,
        ];
    }
}
