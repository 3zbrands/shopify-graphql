<?php

namespace Zzz\ShopifyGraphql\Resources;

use Exception;
use SplFileInfo;
use JsonException;
use Illuminate\Support\Str;
use Saloon\Http\Connector;
use Zzz\ShopifyGraphql\Enums\FileStatus;
use GuzzleHttp\Exception\GuzzleException;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Saloon\Exceptions\Request\RequestException;
use Zzz\ShopifyGraphql\Requests\Files\FileCreate;
use Zzz\ShopifyGraphql\Exceptions\GraphQlException;
use Saloon\Exceptions\Request\FatalRequestException;
use Zzz\ShopifyGraphql\Trait\ValidateGraphQlResponse;
use Zzz\ShopifyGraphql\Responses\Files\FilesResponse;
use Zzz\ShopifyGraphql\Responses\Files\FileCreateResponse;
use Zzz\ShopifyGraphql\Responses\Files\MediaImageResponse;
use Zzz\ShopifyGraphql\Responses\Files\FilesCreateResponse;
use Zzz\ShopifyGraphql\Requests\Files\StagedUploadsCreate;
use Zzz\ShopifyGraphql\Responses\Files\StageTargetResponse;
use Zzz\ShopifyGraphql\Responses\Files\StageUploadsResponse;

class Files
{
    use AlwaysThrowOnErrors;
    use ValidateGraphQlResponse;

    public function __construct(protected Connector $api)
    {
    }

    public function uploadFile(SplFileInfo $fileInfo, bool $waitForFileToBeReady = true)
    {
        $response = $this->createStagedUploads($fileInfo);

        $this->uploadFileToShopify($fileInfo, $response->targets->first());

        return $this->uploadFromUrl($response->targets->first()->resourceUrl(), $waitForFileToBeReady);
    }

    public function uploadFromUrl(string $url, bool $waitForFileToBeReady = true)
    {
        $response = $this->api->send(new FileCreate($url));

        $this->validate($response);

        $file = (new FilesCreateResponse($response->json('data.fileCreate.files')))->files->first();

        if (! $waitForFileToBeReady) {
            return $this->getById($file->id());
        }

        return $this->waitForFileToBeReady($file);
    }

    /**
     * @throws Exception
     */
    public function waitForFileToBeReady(FileCreateResponse $response, int $tries = 5): ?MediaImageResponse
    {
        $currentTry = 0;

        while ($currentTry <= $tries) {
            $file = $this->getById($response->id());

            if ($file->status() === FileStatus::READY) {
                return $file;
            }

            sleep(1);

            $currentTry = $currentTry + 1;
        }

        throw new Exception('Timeout waiting for file to be ready');
    }

    public function all(
        int|null $first = 10,
        int|null $last = null,
        bool $reverse = true,
        string|null $after = null,
        string|null $before = null,
        string|null $searchQuery = null,
    )
    {
        $response = $this->api->send(new \Zzz\ShopifyGraphql\Requests\Files\Files(
            $first,
            $last,
            $reverse,
            $after,
            $before,
            $searchQuery
        ));

        return new FilesResponse($response->json('data.files.edges'));
    }

    public function getById(string $id): ?MediaImageResponse
    {
        $id = Str::after($id, 'gid://shopify/MediaImage/');

        return $this->all(first: 1, searchQuery: "id:{$id}")->files->first();
    }

    public function getByFilename(string $filename): ?MediaImageResponse
    {
        $filename = Str::before($filename, '?');

        return $this->all(first: 1, searchQuery: "filename:{$filename}")->files->first();
    }

    public function getByStatus(FileStatus $status): FilesResponse
    {
        return $this->all(searchQuery: "status:{$status->value}");
    }

    /**
     * @throws FatalRequestException
     * @throws GraphQlException
     * @throws RequestException
     * @throws JsonException
     */
    protected function createStagedUploads(SplFileInfo $fileInfo): StageUploadsResponse
    {
        $response = $this->api->send(new StagedUploadsCreate($fileInfo));

        $this->validate($response);

        return new StageUploadsResponse($response->json('data.stagedUploadsCreate'));
    }

    /**
     * @throws GuzzleException
     */
    protected function uploadFileToShopify(SplFileInfo $fileInfo, StageTargetResponse $stageTarget): void
    {
        $stageTarget->upload($fileInfo);
    }
}
