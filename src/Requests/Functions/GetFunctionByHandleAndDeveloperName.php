<?php

namespace Zzz\ShopifyGraphql\Requests\Functions;

use Exception;
use Zzz\ShopifyGraphql\Requests\QueryRequest;
use Zzz\ShopifyGraphql\Requests\Cart\Traits\CommonQueryFields;

class GetFunctionByHandleAndDeveloperName extends QueryRequest
{
    use CommonQueryFields;

    public function __construct(protected string $functionHandle, protected string $developerName)
    {
        //
    }

    public function graphQuery(): string
    {
        return <<<QUERY

        QUERY;
    }
}
