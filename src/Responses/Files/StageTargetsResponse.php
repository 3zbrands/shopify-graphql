<?php

namespace Zzz\ShopifyGraphql\Responses\Files;

use Iterator;
use Money\Money;
use Money\Currency;
use Saloon\Http\Response;
use Illuminate\Support\Arr;
use Zzz\ShopifyGraphql\Shopify;
use Illuminate\Support\Collection;
use Zzz\ShopifyGraphql\Trait\ConvertCurrencyType;
use Zzz\ShopifyGraphql\Responses\Cart\CartLinesResponse;
use Zzz\ShopifyGraphql\Responses\Cart\DiscountCodeResponse;
use Zzz\ShopifyGraphql\Responses\Cart\DiscountAllocationResponse;

class StageTargetsResponse implements Iterator
{
    private Collection $targets;
    private int $position = 0;

    public function __construct(readonly public array $stagedTargets)
    {
        $this->targets = collect($this->stagedTargets)->map(function (array $target) {
            return new StageTargetResponse(
                $target['url'],
                $target['resourceUrl'],
                $target['parameters'],
            );
        });
    }

    public function current(): mixed
    {
        return $this->targets[$this->position];
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
        return isset($this->targets[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }
}
