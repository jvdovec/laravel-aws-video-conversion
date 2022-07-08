<?php

namespace App\Services;

use Exception;

class PathGeneratorService
{
    private string $videoInputFilename;

    private ?string $videoInputExtension;

    private string $videoOutputFilename;

    private string $videoOutputExtension;

    private string $driverVideoInput;

    private string $driverVideoOutput;

    private string $driverVideoThumbnails;

    private string $bucketNameVideoInput;

    private string $bucketNameVideoOutput;

    private string $bucketNameVideoThumbnails;


    /**
     * @throws Exception
     */
    public function __construct(string $pathToUploadedVideoInputFile, string $targetExtension)
    {
        $parsedPathOfUploadedVideoInputFile = pathinfo($pathToUploadedVideoInputFile);

        $filenameOfUploadedVideoInputFile = $parsedPathOfUploadedVideoInputFile['filename'];

        if (! $filenameOfUploadedVideoInputFile) {
            throw new Exception('Could not obtain the filename from path');
        }

        $extensionOfUploadedVideoInputFile = $parsedPathOfUploadedVideoInputFile['extension'] ?? null;

        $this->videoInputFilename = $filenameOfUploadedVideoInputFile;
        $this->videoInputExtension = $extensionOfUploadedVideoInputFile;

        $this->videoOutputFilename = $filenameOfUploadedVideoInputFile;
        $this->videoOutputExtension = $targetExtension;

        $chosenCloudDiskForVideoInput = config('filesystems.cloud_disk_video_input');
        $chosenCloudDiskForVideoOutput = config('filesystems.cloud_disk_video_output');
        $chosenCloudDiskForVideoThumbnails = config('filesystems.cloud_disk_video_thumbnails');

        $this->driverVideoInput = config('filesystems.disks.'.$chosenCloudDiskForVideoInput.'.driver');
        $this->driverVideoOutput = config('filesystems.disks.'.$chosenCloudDiskForVideoOutput.'.driver');
        $this->driverVideoThumbnails = config('filesystems.disks.'.$chosenCloudDiskForVideoThumbnails.'.driver');

        $this->bucketNameVideoInput = config('filesystems.disks.'.$chosenCloudDiskForVideoInput.'.bucket');
        $this->bucketNameVideoOutput = config('filesystems.disks.'.$chosenCloudDiskForVideoOutput.'.bucket');
        $this->bucketNameVideoThumbnails = config('filesystems.disks.'.$chosenCloudDiskForVideoThumbnails.'.bucket');
    }

    protected function getVideoInputFilenameWithExtension(): string
    {
        return $this->videoInputExtension ? "$this->videoInputFilename.$this->videoInputExtension" : $this->videoInputFilename;
    }

    public function getVideoOutputFilenameWithExtension(): string
    {
        return "$this->videoOutputFilename.$this->videoOutputExtension";
    }

    public function getFullyQualifiedPathForVideoInputWithExtension(): string
    {
        return  "$this->driverVideoInput://$this->bucketNameVideoInput/{$this->getVideoInputFilenameWithExtension()}";
    }

    public function getFullyQualifiedPathForVideoOutputWithoutExtension(): string
    {
        return "$this->driverVideoOutput://$this->bucketNameVideoOutput/$this->videoInputFilename";
    }

    public function getFullyQualifiedPathForVideoThumbnailsFolder(): string
    {
        return "$this->driverVideoThumbnails://$this->bucketNameVideoThumbnails/{$this->getVideoThumbnailsFolder()}/";
    }

    public function getVideoThumbnailsFolder(): string
    {
        return $this->videoInputFilename;
    }
}
