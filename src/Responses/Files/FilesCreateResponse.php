<?php

namespace Zzz\ShopifyGraphql\Responses\Files;

use Iterator;
use Illuminate\Support\Collection;

class FilesCreateResponse implements Iterator
{
    public Collection $files;
    private int $position = 0;

    public function __construct(array $files)
    {
        $this->files = collect($files)->map(function (array $file) {
            return new FileCreateResponse($file);
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
