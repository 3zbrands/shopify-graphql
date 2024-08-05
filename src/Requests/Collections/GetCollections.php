<?php

namespace Zzz\ShopifyGraphql\Requests\Collections;

use Zzz\ShopifyGraphql\Trait\HasEdges;
use Zzz\ShopifyGraphql\Requests\QueryRequest;
use Zzz\ShopifyGraphql\Requests\Cart\Traits\CommonQueryFields;

class GetCollections extends QueryRequest
{
    use CommonQueryFields;
    use HasEdges;

    public function graphQuery(): string
    {
        return <<<QUERY
            collections(
                {$this->edgeFilters()}
            ) {
                edges {
                    cursor
                    node {
                        {$this->commonCollectionFields()}
                    }
                }
            }
        QUERY;
    }
}
