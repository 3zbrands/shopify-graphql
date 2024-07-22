<?php

namespace Zzz\ShopifyGraphql\Requests\Files;

use Zzz\ShopifyGraphql\Requests\QueryRequest;

class Files extends QueryRequest
{
    public function __construct(
        protected int|null $first = 10,
        protected int|null $last = null,
        protected bool $reverse = true,
        protected string|null $after = null,
        protected string|null $before = null,
        protected string|null $searchQuery = null,
    )
    {
    }

    public function graphQuery(): string
    {
        $first = $this->first ? "first: {$this->first}" : null;
        $last = $this->last ? "last: {$this->last}" : null;
        $reverse = "reverse: " . ($this->reverse ? 'true' : 'false');
        $after = $this->after ? "after: \"{$this->after}\"" : null;
        $before = $this->before ? "before: \"{$this->before}\"" : null;
        $searchQuery = $this->searchQuery ? "query: \"$this->searchQuery\"" : null;

        return <<<QUERY
            files(
                {$first}
                {$last}
                {$reverse}
                {$after}
                {$before}
                {$searchQuery}
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
