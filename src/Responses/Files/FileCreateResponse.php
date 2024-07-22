<?php

namespace Zzz\ShopifyGraphql\Responses\Files;

use Zzz\ShopifyGraphql\Enums\FileStatus;

class FileCreateResponse
{
    public function __construct(public readonly array $file)
    {
    }

    public function id(): string
    {
        return $this->file['id'];
    }

    public function status(): FileStatus
    {
        return FileStatus::from($this->file['fileStatus']);
    }

    public function alt(): string
    {
        return $this->file['alt'];
    }

    public function url(): string|null
    {
        return $this->file['preview']['image']['url'] ?? null;
    }
}
