<?php

namespace Zzz\ShopifyGraphql\Requests\Files;

use Zzz\ShopifyGraphql\Requests\MutationRequest;

class CreateFile extends MutationRequest
{
    public function __construct(protected string $url)
    {
    }

    public function graphQuery(): string
    {
        $files = [
            'originalSource' => $this->url,
        ];


        $files = $this->convertArrayWithKeysToGraphQlSyntax($files);

        return <<<QUERY
            fileCreate(
                files: $files
            ) {
                files {
                    id
                    fileStatus
                    alt
                    preview {
                        image {
                            url
                        }
                    }
                }
                userErrors {
                    code
                    field
                    message
                }
            }
        QUERY;
    }
}
