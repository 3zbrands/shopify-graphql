<?php

namespace Zzz\ShopifyGraphql\Responses\Files;

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

class StageUploadsResponse
{
    public Collection $targets;
    public function __construct(readonly public array $response)
    {
        $this->targets = collect($this->response['stagedTargets'])->map(function (array $target) {
            return new StageTargetResponse($target['url'], $target['resourceUrl'], $target['parameters']);
        });
    }

    public function json(string $key = '')
    {
        return Arr::get($this->response, $key);
    }

//    public function stageTargets(): StageTargetsResponse
//    {
//        return new StageTargetsResponse($this->json('stagedTargets'));
//    }
}
