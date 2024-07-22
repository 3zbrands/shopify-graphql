<?php

namespace Zzz\ShopifyGraphql\Requests;

use Illuminate\Support\Str;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Traits\Body\HasJsonBody;

abstract class Request extends \Saloon\Http\Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    protected string $returnNodes = '';

    public function return(array|string $nodes): static
    {
        $this->returnNodes = is_array($nodes)
            ? $this->convertArrayToGraphQlSyntax($nodes)
            : $nodes;

        return $this;
    }

    public function resolveEndpoint(): string
    {
        return '';
    }

    public function convertArrayWithKeysToGraphQlSyntax(array $attributes, array $enums = []): string
    {
        $input = Str::of('');

        foreach ($attributes as $key => $value) {
            $input = in_array($key, $enums)
                ? $input->append("{$key}: {$value}, ")
                : $input->append("{$key}: \"{$value}\", ");
        }

        return $input->trim()->replaceLast(',', '')->start('{')->finish('}')->toString();
    }

    public function convertArrayToGraphQlSyntax($fields): string
    {
        return Str::of(json_encode($fields, JSON_PRETTY_PRINT))
            ->replaceMatches('~"\d+":\s~', '')
            ->replace(['"', ':'], '')
            ->replace(['[', ']'], ['{', '}'])
            ->replaceFirst("{", "")
            ->replaceLast("}", "")
            ->toString();
    }
}
