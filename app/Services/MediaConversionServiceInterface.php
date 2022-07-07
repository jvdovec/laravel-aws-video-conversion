<?php

namespace App\Services;

interface MediaConversionServiceInterface
{
    public function queueConversion(
        string $videoInputFullyQualifiedPathWithExtension, // (example: s3://video-input-bucket/video.mkv)
        string $videoOutputFullyQualifiedPathWithoutExtension, // (example: s3://video-output-bucket/video)
        string $videoThumbnailsFullyQualifiedPath // (example: s3://video-thumbnails-bucket/folder-for-thumbnails-for-video/)
    ): string;

    public function getConversionJobStatus(string $conversionJobId): array;

    public function isConversionJobComplete(array $conversionJobStatus): bool;

    public function getTargetExtension(): string;
}
