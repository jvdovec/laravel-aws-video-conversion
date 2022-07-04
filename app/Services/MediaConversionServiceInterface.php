<?php

namespace App\Services;

interface MediaConversionServiceInterface
{
    /**
     * @param string $videoInputFullyQualifiedPathWithExtension (example: s3://video-input-bucket/video.mkv)
     * @param string $videoOutputFullyQualifiedPathWithoutExtension  (example: s3://video-output-bucket/video)
     * @param string $videoThumbnailsFullyQualifiedPath (example: s3://video-thumbnails-bucket/folder-for-thumbnails-for-video/)
     * @return string
     */
    public function queueConversion(
        string $videoInputFullyQualifiedPathWithExtension,
        string $videoOutputFullyQualifiedPathWithoutExtension,
        string $videoThumbnailsFullyQualifiedPath
    ) : string;

    /**
     * @param string $conversionJobId
     * @return array
     */
    public function getConversionJobStatus(string $conversionJobId) : array;

    /**
     * @param array $conversionJobStatus
     * @return bool
     */
    public function isConversionJobComplete(array $conversionJobStatus) : bool;

    public function getTargetExtension() : string;
}
