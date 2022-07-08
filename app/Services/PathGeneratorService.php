<?php

namespace App\Services;

use Exception;

class PathGeneratorService
{
    private string $videoInputWithoutExtension;

    private string $videoInputWithExtension;

    private string $videoOutputWithoutExtension;

    private string $videoOutputExtension;

    private string $videoOutputWithExtension;

    private string $cloudDiskVideoInput;

    private string $cloudDiskVideoOutput;

    private string $cloudDiskVideoThumbnails;

    private string $driverVideoInput;

    private string $driverVideoOutput;

    private string $driverVideoThumbnails;

    private string $bucketNameVideoInput;

    private string $bucketNameVideoOutput;

    private string $bucketNameVideoThumbnails;

    /**
     * PathGeneratorService constructor.
     * @param string $path
     * @param string $targetExtension
     * @throws Exception
     */
    public function __construct(string $path, string $targetExtension)
    {
        $parsedPath = pathinfo($path);

        $filename = $parsedPath['filename'] ?? null;

        if (! $filename) {
            throw new Exception('Could not obtain the filename from path');
        }

        $extension = $parsedPath['extension'] ?? null;

        $this->videoInputWithoutExtension = $filename;
        $this->videoInputWithExtension = $extension ? "$filename.$extension" : $filename;

        $this->videoOutputWithoutExtension = $filename;
        $this->videoOutputExtension = $targetExtension;
        $this->videoOutputWithExtension = "$filename.$this->videoOutputExtension";

        $this->cloudDiskVideoInput = config('filesystems.cloud_disk_video_input');
        $this->cloudDiskVideoOutput = config('filesystems.cloud_disk_video_output');
        $this->cloudDiskVideoThumbnails = config('filesystems.cloud_disk_video_thumbnails');

        $this->driverVideoInput = config('filesystems.disks.'.$this->cloudDiskVideoInput.'.driver');
        $this->driverVideoOutput = config('filesystems.disks.'.$this->cloudDiskVideoOutput.'.driver');
        $this->driverVideoThumbnails = config('filesystems.disks.'.$this->cloudDiskVideoThumbnails.'.driver');

        $this->bucketNameVideoInput = config('filesystems.disks.'.$this->cloudDiskVideoInput.'.bucket');
        $this->bucketNameVideoOutput = config('filesystems.disks.'.$this->cloudDiskVideoOutput.'.bucket');
        $this->bucketNameVideoThumbnails = config('filesystems.disks.'.$this->cloudDiskVideoThumbnails.'.bucket');
    }

    public function getFullyQualifiedPathForVideoInputWithExtension(): string
    {
        return  "{$this->driverVideoInput}://$this->bucketNameVideoInput/$this->videoInputWithExtension";
    }

    public function getFullyQualifiedPathForVideoOutputWithoutExtension(): string
    {
        return "{$this->driverVideoInput}://$this->bucketNameVideoOutput/$this->videoInputWithoutExtension";
    }

    public function getFullyQualifiedPathForVideoThumbnailsFolder(): string
    {
        return "{$this->driverVideoThumbnails}://$this->bucketNameVideoThumbnails/{$this->getVideoThumbnailsFolder()}/";
    }

    public function getVideoOutputWithExtension(): string
    {
        return $this->videoOutputWithExtension;
    }

    public function getVideoThumbnailsFolder(): string
    {
        return $this->videoInputWithoutExtension;
    }
}
