<?php

namespace Zzz\ShopifyGraphql\Responses\Publications;

use Illuminate\Support\Collection;

class PublicationsResponse
{
    public readonly Collection $all;

    public function __construct(public readonly array $publications = [])
    {
        $this->all = collect($this->publications['edges'])->map(function (array $publication) {
            return new PublicationResponse($publication);
        });
    }

    public function getPublicationByName(string $name): PublicationResponse|null
    {
        return $this->all->first(fn (PublicationResponse $publication) => $publication->name() === $name);
    }
}
