<?php

namespace Zzz\ShopifyGraphql\Responses\Files;

use SplFileInfo;
use GuzzleHttp\Psr7;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class StageTargetResponse
{
    public function __construct(
        readonly public string $url,
        readonly public string $resourceUrl,
        readonly public array $parameters,
    )
    {
        //
    }

    public function url(): string
    {
        return $this->url;
    }

    public function resourceUrl(): string
    {
        return $this->resourceUrl;
    }

    public function parameters(): array
    {
        return $this->parameters;
    }

    protected function parametersForGuzzleHeaders(): array
    {
        return collect($this->parameters)
            ->mapWithKeys(function (array $parameterSet) {
                return [$parameterSet['name'] => $parameterSet['value']];
            })->toArray();
    }

    /**
     * @throws GuzzleException
     */
    public function upload(SplFileInfo $file): \Psr\Http\Message\ResponseInterface
    {
        $client = new Client();

        return $client->put($this->resourceUrl, [
            'query' => parse_url($this->url)['query'],
            'headers' => $this->parametersForGuzzleHeaders(),
            'body' => Psr7\Utils::tryFopen($file->getPathname(), 'r')
        ]);
    }
}
