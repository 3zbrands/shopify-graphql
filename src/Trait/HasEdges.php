<?php

namespace Zzz\ShopifyGraphql\Trait;

trait HasEdges
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
        //
    }

    public function edgeFilters()
    {
        $first = $this->first ? "first: {$this->first}" : null;
        $last = $this->last ? "last: {$this->last}" : null;
        $reverse = "reverse: " . ($this->reverse ? 'true' : 'false');
        $after = $this->after ? "after: \"{$this->after}\"" : null;
        $before = $this->before ? "before: \"{$this->before}\"" : null;
        $searchQuery = $this->searchQuery ? "query: \"$this->searchQuery\"" : null;

        return collect([$first, $last, $reverse, $after, $before, $searchQuery])
            ->filter()
            ->values()
            ->implode(PHP_EOL);
    }
}
