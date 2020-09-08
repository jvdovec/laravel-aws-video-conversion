<?php

namespace App\Services;

interface MediaConversionServiceInterface
{
    public function queueConversion(string $videoInputFullyQualifiedPathWithExtension, string $videoOutputFullyQualifiedPathWithoutExtension, string $videoThumbnailsFullyQualifiedPath) : string;

    public function getConversionJobStatus(string $conversionJobId) : array;

    public function isConversionJobComplete(array $conversionJobStatus) : bool;

    public function getTargetExtension() : string;
}
