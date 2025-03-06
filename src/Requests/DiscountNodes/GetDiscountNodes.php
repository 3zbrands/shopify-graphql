<?php

namespace Zzz\ShopifyGraphql\Requests\DiscountNodes;

use Zzz\ShopifyGraphql\Trait\HasEdges;
use Zzz\ShopifyGraphql\Requests\QueryRequest;
use Zzz\ShopifyGraphql\Requests\Cart\Traits\CommonQueryFields;

class GetDiscountNodes extends QueryRequest
{
    use CommonQueryFields;
    use HasEdges;

    public function __construct(protected $discountTitle)
    {
        
    }

    public function graphQuery(): string
    {
        $titleFilter = sprintf('title:%s', addslashes($this->discountTitle."*"));

        return <<<QUERY
            discountNodes(query: "status:active AND $titleFilter", first: 10) {
                edges {
                node {
                    id
                    discount {
                        ... on DiscountCodeBasic {
                            title
                            status
                            combinesWith {
                            productDiscounts
                            }
                        }
                    }
                }
                }
            }
        QUERY;
    }
}
