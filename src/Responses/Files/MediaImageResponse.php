<?php

namespace Zzz\ShopifyGraphql\Responses\Files;

use Zzz\ShopifyGraphql\Enums\FileStatus;

class MediaImageResponse
{
    public function __construct(public readonly array $file)
    {
    }

    public function status(): FileStatus
    {
        return FileStatus::from($this->file['fileStatus']);
    }

    public function id(): string
    {
        return $this->file['id'];
    }

    public function imageId(): string
    {
        return $this->file['image']['id'];
    }

    public function url(): string
    {
        return $this->file['image']['originalSrc'];
    }

    public function height(): int
    {
        return $this->file['image']['height'];
    }

    public function width(): int
    {
        return $this->file['image']['width'];
    }

//    public function status(): FileStatus
//    {
//        return FileStatus::from($this->file['fileStatus']);
//    }
//
//    public function alt(): string
//    {
//        return $this->file['alt'];
//    }
//
//    public function url(): string|null
//    {
//        return $this->file['preview']['image']['url'] ?? null;
//    }
}
