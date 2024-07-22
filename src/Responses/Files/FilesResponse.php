<?php

namespace Zzz\ShopifyGraphql\Responses\Files;

use Iterator;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class FilesResponse implements Iterator
{
    public Collection $files;
    private int $position = 0;

    public function __construct(array $files)
    {
        $this->files = collect($files)->map(function (array $file) {
            return match (true) {
                Str::startsWith($file['node']['id'], 'gid://shopify/MediaImage/') => new MediaImageResponse($file['node']),
                default => throw new Exception("Unknown file type: {$file['node']['id']}"),
            };
        });
    }

    public function current(): mixed
    {
        return $this->files[$this->position];
    }

    public function next(): void
    {
        $this->position = $this->position + 1;
    }

    public function key(): mixed
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->files[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }
}
