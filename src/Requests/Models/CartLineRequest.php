<?php

namespace Zzz\ShopifyGraphql\Requests\Models;

use Exception;
use Illuminate\Support\Str;
use Zzz\ShopifyGraphql\Enums\GID;
use Zzz\ShopifyGraphql\Responses\Cart\CartLineResponse;

class CartLineRequest
{
    /**
     * @throws Exception
     */
    public function __construct(
        public readonly string $gid,
        public int $quantity = 1,
        public array $attributes = [],
    )
    {
        if (! Str::startsWith($this->gid, 'gid://')) {
            throw new Exception('The $id must start with "gid://"');
        }
    }

    public static function gid(string $gid, int $quantity = 1, array $attributes = [])
    {
        return new self($gid, $quantity, $attributes);
    }

    public static function fromResponse(CartLineResponse $response)
    {
        return new self(
            gid: $response->id(),
            quantity: $response->quantity,
            attributes: $response->attributes()->toArray(),
        );
    }

    public static function productVariant(string $productVariantId, int $quantity = 1, array $attributes = []): CartLineRequest
    {
        return new self(
            gid: GID::PRODUCT_VARIANT->path($productVariantId),
            quantity: $quantity,
            attributes: $attributes,
        );
    }

    public function attribute(string $key, string $value): static
    {
        $this->attributes[] = [
            'key' => $key,
            'value' => $value,
        ];

        return $this;
    }

    public function attributes(): string
    {
        if (! $this->attributes) {
            return '';
        }

        $graphQlFormattedAttributes = '';

        foreach ($this->attributes as $attributeKey => $attributeValue) {
            $key = $attributeValue['key'] ?? $attributeKey;
            $value = $attributeValue['value'] ?? $attributeValue;

            if (! $value) {
                continue;
            }

            if (is_iterable($value)) {
                $value = json_encode($value);
            }

            $graphQlFormattedAttributes .= <<<ATT
                {
                    key: "{$key}"
                    value: "{$value}"
                },
            ATT;
        }

        return <<<ATTR
            attributes: [
                $graphQlFormattedAttributes
            ],
        ATTR;
    }
}
