<?php

namespace Zzz\ShopifyGraphql\Responses\Collections;

use Iterator;
use Exception;
use ArrayAccess;
use Illuminate\Support\Collection;

class CollectionsResponse implements Iterator, ArrayAccess
{
    public readonly Collection $all;

    private int $position = 0;

    public function __construct(public readonly array $collections = [])
    {
        $this->all = collect($this->collections['edges'])->map(function (array $collection) {
            return new CollectionResponse($collection);
        });
    }

    public function current(): mixed
    {
        return $this->all[$this->position];
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
        return isset($this->all[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->all[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->all[$offset];
    }

    /**
     * @throws Exception
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new Exception('Not allowed to set modify a collection');
    }

    /**
     * @throws Exception
     */
    public function offsetUnset(mixed $offset): void
    {
        throw new Exception('Not allowed to modify a collection');
    }
}
