<?php

namespace App\Http\Controllers;

use App\Actions\UploadFileToCloudAction;
use App\Http\Requests\DoConversionRequest;
use App\Http\Requests\DownloadVideoOutputRequest;
use App\Http\Requests\DownloadVideoThumbnailRequest;
use App\Services\MediaConversionServiceInterface;
use App\Services\PathGeneratorService;
use Illuminate\Support\Facades\Storage;

class ConversionController extends Controller
{
    public function showUploadForm(): \Illuminate\Contracts\View\View
    {
        return view('upload');
    }

    /**
     * @throws \Exception
     */
    public function doConversion(
        DoConversionRequest $request,
        MediaConversionServiceInterface $mediaConversionService,
        UploadFileToCloudAction $uploadFileToCloudAction
    ): \Illuminate\Http\RedirectResponse {
        $pathToUploadedVideoInputFile = $uploadFileToCloudAction->handle($request->getUploadedFile());

        /*
         * 2. QUEUE CONVERSION
         */
        $pathGeneratorService = new PathGeneratorService($pathToUploadedVideoInputFile);
        $conversionJobId = $mediaConversionService->queueConversion(
            $pathGeneratorService->getFullyQualifiedPathForVideoInputFilenameWithExtension(),
            $pathGeneratorService->getFullyQualifiedPathForVideoOutputFilename(),
            $pathGeneratorService->getFullyQualifiedPathForVideoThumbnailsFolder()
        );

        /*
         * 3. REDIRECT TO STATUS PAGE
         */
        return redirect(
            route(
                'get-conversion-job-status',
                [
                    'conversionJobId' => $conversionJobId,
                    'pathToUploadedVideoInputFile' => $pathToUploadedVideoInputFile,
                ]
            )
        );
    }

    /**
     * @throws \Exception
     */
    public function getConversionJobStatus(string $pathToUploadedVideoInputFile, string $conversionJobId, MediaConversionServiceInterface $mediaConversionService): \Illuminate\Contracts\View\View
    {
        $videoOutputFileKey = false;
        $videoThumbnailsFileKeys = [];

        $conversionJobStatus = $mediaConversionService->getConversionJobStatus($conversionJobId);
        $conversionJobStatusToHtml = print_r($conversionJobStatus, true);
        $isConversionJobComplete = $mediaConversionService->isConversionJobComplete($conversionJobStatus);

        if ($isConversionJobComplete) {
            $pathGeneratorService = new PathGeneratorService($pathToUploadedVideoInputFile);
            $videoOutputFileKey = $pathGeneratorService->getVideoOutputFilenameWithExtension();
            $videoThumbnailsFileKeys = Storage::disk(config('filesystems.cloud_disk_video_thumbnails'))->files($pathGeneratorService->getVideoThumbnailsFolder());
        }

        return view(
            'conversion_job_status',
            compact(
                'conversionJobStatusToHtml',
                'conversionJobId',
                'isConversionJobComplete',
                'pathToUploadedVideoInputFile',
                'videoOutputFileKey',
                'videoThumbnailsFileKeys'
            )
        );
    }

    public function downloadVideoOutput(DownloadVideoOutputRequest $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        return Storage::disk(config('filesystems.cloud_disk_video_output'))
            ->download($request->getFileKey());
    }

    public function downloadVideoThumbnail(DownloadVideoThumbnailRequest $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        return Storage::disk(config('filesystems.cloud_disk_video_thumbnails'))
            ->download($request->getFileKey());
    }
}
