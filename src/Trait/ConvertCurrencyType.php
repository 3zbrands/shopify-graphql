<?php

namespace Zzz\ShopifyGraphql\Trait;

trait ConvertCurrencyType
{
    public function dollarsAsFloatToCent($float) : int
    {
        return (int) (string) ($float * 100);
    }
}
