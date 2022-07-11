<?php

namespace App\Services;

use Exception;

class PathGeneratorService
{
    protected string $videoInputFilename;

    protected ?string $videoInputExtension;

    protected string $videoOutputFilename;

    protected string $videoOutputExtension;

    protected string $driverVideoInput;

    protected string $driverVideoOutput;

    protected string $driverVideoThumbnails;

    protected string $bucketNameVideoInput;

    protected string $bucketNameVideoOutput;

    protected string $bucketNameVideoThumbnails;


    /**
     * @throws Exception
     */
    public function __construct(string $pathToUploadedVideoInputFile, string $videoOutputTargetExtension)
    {
        $parsedPathOfUploadedVideoInputFile = pathinfo($pathToUploadedVideoInputFile);

        $filenameOfUploadedVideoInputFile = $parsedPathOfUploadedVideoInputFile['filename'];

        if (! $filenameOfUploadedVideoInputFile) {
            throw new Exception('Could not obtain the filename from path');
        }

        $extensionOfUploadedVideoInputFile = $parsedPathOfUploadedVideoInputFile['extension'] ?? null;

        $this->videoInputFilename = $filenameOfUploadedVideoInputFile;
        $this->videoInputExtension = $extensionOfUploadedVideoInputFile;

        /** @var MediaConversionServiceInterface $mediaConversionService */
        $mediaConversionService = app(MediaConversionServiceInterface::class);

        $this->videoOutputFilename = $filenameOfUploadedVideoInputFile;
        $this->videoOutputExtension = $videoOutputTargetExtension;

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

    public function getVideoInputFilenameWithExtension(): string
    {
        return $this->videoInputExtension ? "$this->videoInputFilename.$this->videoInputExtension" : $this->videoInputFilename;
    }

    public function getVideoOutputFilenameWithExtension(): string
    {
        return "$this->videoOutputFilename.$this->videoOutputExtension";
    }

    public function getFullyQualifiedPathForVideoInputFilenameWithExtension(): string
    {
        return  "$this->driverVideoInput://$this->bucketNameVideoInput/{$this->getVideoInputFilenameWithExtension()}";
    }

    public function getFullyQualifiedPathForVideoOutputFilename(): string
    {
        return "$this->driverVideoOutput://$this->bucketNameVideoOutput/$this->videoInputFilename";
    }

    public function getVideoThumbnailsFolder(): string
    {
        return $this->videoInputFilename;
    }

    public function getFullyQualifiedPathForVideoThumbnailsFolder(): string
    {
        return "$this->driverVideoThumbnails://$this->bucketNameVideoThumbnails/{$this->getVideoThumbnailsFolder()}/";
    }
}
