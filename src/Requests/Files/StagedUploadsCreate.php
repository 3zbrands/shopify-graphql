<?php

namespace Zzz\ShopifyGraphql\Requests\Files;

use SplFileInfo;
use Illuminate\Support\Facades\File;
use Zzz\ShopifyGraphql\Requests\MutationRequest;

class StagedUploadsCreate extends MutationRequest
{
    public function __construct(protected SplFileInfo $file)
    {
    }

    public function graphQuery(): string
    {
        $input = [
            'filename' => $this->file->getFilename(),
            'mimeType' => mime_content_type($this->file->getPathname()),
            'resource' => "IMAGE",
            'fileSize' => $this->file->getSize(),
        ];

        $input = $this->convertArrayWithKeysToGraphQlSyntax($input, enums: ['resource']);

        return <<<QUERY
            stagedUploadsCreate(
                input: $input
            ) {
                stagedTargets {
                    url
                    resourceUrl
                    parameters {
                        name
                        value
                    }
                }
                userErrors {
                    field
                    message
                }
            }
        QUERY;
    }
}
