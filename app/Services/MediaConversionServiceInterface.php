<?php

namespace App\Services;

interface MediaConversionServiceInterface
{
    /**
     *
     * @param string $videoInputFullyQualifiedPathWithExtension
     * @param string $videoOutputFullyQualifiedPathWithoutExtension
     * @param string $videoThumbnailsFullyQualifiedPath
     * @return string
     */
    public function queueConversion(
        string $videoInputFullyQualifiedPathWithExtension,
        string $videoOutputFullyQualifiedPathWithoutExtension,
        string $videoThumbnailsFullyQualifiedPath
    ) : string;

    /**
     *
     * @param string $conversionJobId
     */
    public function getConversionJobStatus(string $conversionJobId) : array;

    /**
     *
     * @param array $conversionJobStatus
     */
    public function isConversionJobComplete(array $conversionJobStatus) : bool;

    public function getTargetExtension() : string;
}
