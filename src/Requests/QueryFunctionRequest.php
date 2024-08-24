<?php

namespace Zzz\ShopifyGraphql\Requests;

abstract class QueryFunctionRequest extends Request
{
    abstract public function graphQuery(): string;

    public function defaultBody(): array
    {
        $requestGraphQuery = $this->graphQuery();

        $query = <<<QUERY
            query functions {
                $requestGraphQuery
            }
        QUERY;

        return [
            'query' => $query,
        ];
    }
}
