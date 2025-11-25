<?php

namespace App\Enums;

use App\Foundations\Enum;

enum UploadFileType: string
{
    use Enum;

    // Case section started
    case FILE = 'files';
    case IMAGE = 'photos';
}
