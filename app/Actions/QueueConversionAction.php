<?php

namespace App\Actions;

use App\Exceptions\PathGeneratorService\FilenameNotPresentException;
use App\Services\MediaConversionServiceInterface;
use App\Services\PathGeneratorService;

class QueueConversionAction
{
    /**
     * @throws FilenameNotPresentException
     */
    public function handle(string $pathToUploadedVideoInputFile): string
    {
        $mediaConversionService = $this->instantiateMediaConversionService();

        $pathGeneratorService = PathGeneratorService::create($pathToUploadedVideoInputFile, $mediaConversionService->getTargetExtension());

        $conversionJobId = $mediaConversionService->queueConversion(
            $pathGeneratorService->getFullyQualifiedPathForVideoInputFilenameWithExtension(),
            $pathGeneratorService->getFullyQualifiedPathForVideoOutputFilename(),
            $pathGeneratorService->getFullyQualifiedPathForVideoThumbnailsFolder()
        );

        return $conversionJobId;
    }

    protected function instantiateMediaConversionService(): MediaConversionServiceInterface
    {
        return app(MediaConversionServiceInterface::class);
    }
}
