<?php

namespace App\Actions;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadFileToCloudAction
{
    public function handle(UploadedFile $uploadedFile): string
    {
        $cloudDiskVideoInput = Storage::disk(config('filesystems.cloud_disk_video_input'));
        return $cloudDiskVideoInput->put('', $uploadedFile);
    }
}
