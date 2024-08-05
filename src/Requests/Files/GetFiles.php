<?php

namespace Zzz\ShopifyGraphql\Requests\Files;

use Zzz\ShopifyGraphql\Trait\HasEdges;
use Zzz\ShopifyGraphql\Requests\QueryRequest;

class GetFiles extends QueryRequest
{
    use HasEdges;

    public function graphQuery(): string
    {
        return <<<QUERY
            files(
                {$this->edgeFilters()}
            ) {
                edges {
                    cursor
                    node {
                        fileStatus
                        
                        ... on MediaImage {
                            id
                            image {
                                id
                                originalSrc: url
                                width
                                height
                            }
                        }
                        
                        ... on GenericFile {
                            id
                            url
                        }
                        
                        ... on Video {
                            id
                            duration
                            preview {
                                status
                                image {
                                    id
                                    width
                                    height
                                    url
                                }
                            }
                            originalSource {
                                url
                                width
                                height
                                format
                                mimeType
                            }
                            sources {
                                url
                                width
                                height
                                format
                                mimeType
                            }
                        }
                    }
                }
            }
        QUERY;
    }
}
