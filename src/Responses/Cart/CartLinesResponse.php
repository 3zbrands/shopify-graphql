<?php

namespace Zzz\ShopifyGraphql\Responses\Cart;

use Iterator;
use Illuminate\Support\Collection;

class CartLinesResponse implements Iterator
{
    public readonly Collection $all;

    private int $position = 0;

    public function __construct(protected ?array $cartLines = [])
    {
        $this->all = collect($this->cartLines)->mapInto(CartLineResponse::class);
    }

    public function findByProductVariantId($id): ?CartLineResponse
    {
        return $this->all->first(fn (CartLineResponse $line) => $line->productVariantId() == "gid://shopify/ProductVariant/$id");
    }

    public function findByGid($gid): ?CartLineResponse
    {
        return $this->all->first(fn (CartLineResponse $line) => $line->id == $gid);
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

    public function __call(string $method, $parameters)
    {
        dd($method, $parameters);
    }
}
