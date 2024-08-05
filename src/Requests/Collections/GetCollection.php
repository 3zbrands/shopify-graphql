<?php

namespace Zzz\ShopifyGraphql\Requests\Collections;

use Zzz\ShopifyGraphql\Trait\HasEdges;
use Zzz\ShopifyGraphql\Requests\QueryRequest;
use Zzz\ShopifyGraphql\Requests\Cart\Traits\CommonQueryFields;

class GetCollection extends QueryRequest
{
    use CommonQueryFields;
    use HasEdges;

    public function __construct(
        protected string $collectionId,
        protected int|null $first = 10,
        protected int|null $last = null,
        protected bool $reverse = true,
        protected string|null $after = null,
        protected string|null $before = null,
        protected string|null $searchQuery = null,
    )
    {
        //
    }

    public function graphQuery(): string
    {
        return <<<QUERY
            collection(id: "$this->collectionId") {
                {$this->commonCollectionFields()}
                products(
                    {$this->edgeFilters()}
                ) {
                    edges {
                        cursor
                        node {
                            id
                            title
                        }
                    }
                }
            }
        QUERY;
    }
}
