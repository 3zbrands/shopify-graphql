<?php

namespace Zzz\ShopifyGraphql\Enums;

use Exception;
use Illuminate\Support\Str;

enum GID
{
    case CART;
    case CART_LINE;
    case PRODUCT_VARIANT;

    public function path($id): string
    {
        return match ($this) {
            self::CART => Str::startsWith($id, 'gid://shopify/Cart') ? $id : "gid://shopify/Cart/$id",
            self::CART_LINE => Str::startsWith($id, 'gid://shopify/CartLine') ? $id : "gid://shopify/CartLine/$id",
            self::PRODUCT_VARIANT => Str::startsWith($id, 'gid://shopify/ProductVariant') ? $id : "gid://shopify/ProductVariant/{$id}",
        };
    }

    /**
     * @throws Exception
     */
    public static function caseFromString(string $gid): GID
    {
        return match (true) {
            Str::startsWith($gid, 'gid://shopify/Cart') => self::CART,
            Str::startsWith($gid, 'gid://shopify/CartLine') => self::CART_LINE,
            Str::startsWith($gid, 'gid://shopify/ProductVariant') => self::PRODUCT_VARIANT,
            default => throw new Exception('Unknown GID type'),
        };
    }
}
