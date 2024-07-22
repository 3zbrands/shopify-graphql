<?php

namespace Zzz\ShopifyGraphql\Enums;

enum FileStatus: string
{
    case FAILED = 'FAILED';
    case PROCESSING = 'PROCESSING';
    case READY = 'READY';
    case UPLOADED = 'UPLOADED';
}
