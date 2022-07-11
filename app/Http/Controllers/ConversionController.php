<?php

namespace App\Http\Controllers;

use App\Actions\QueueConversionAction;
use App\Actions\UploadFileToCloudAction;
use App\Http\Requests\DoConversionRequest;
use App\Http\Requests\DownloadVideoOutputRequest;
use App\Http\Requests\DownloadVideoThumbnailRequest;
use App\Services\MediaConversionServiceInterface;
use App\Services\PathGeneratorService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ConversionController extends Controller
{
    public function showUploadForm(): View
    {
        return view('upload');
    }

    /**
     * @throws Exception
     */
    public function doConversion(
        DoConversionRequest $request,
        UploadFileToCloudAction $uploadFileToCloudAction,
        QueueConversionAction $queueConversionAction
    )
    : RedirectResponse
    {
        $pathToUploadedVideoInputFile = $uploadFileToCloudAction->handle($request->getUploadedFile());

        $conversionJobId = $queueConversionAction->handle($pathToUploadedVideoInputFile);

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
     * @throws Exception
     */
    public function getConversionJobStatus(string $pathToUploadedVideoInputFile, string $conversionJobId, MediaConversionServiceInterface $mediaConversionService): View
    {
        $videoOutputFileKey = false;
        $videoThumbnailsFileKeys = [];

        $conversionJobStatus = $mediaConversionService->getConversionJobStatus($conversionJobId);
        $conversionJobStatusToHtml = print_r($conversionJobStatus, true);
        $isConversionJobComplete = $mediaConversionService->isConversionJobComplete($conversionJobStatus);

        if ($isConversionJobComplete) {
            $pathGeneratorService = new PathGeneratorService($pathToUploadedVideoInputFile, $mediaConversionService->getTargetExtension());
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

    public function downloadVideoOutput(DownloadVideoOutputRequest $request): StreamedResponse
    {
        return Storage::disk(config('filesystems.cloud_disk_video_output'))
            ->download($request->getFileKey());
    }

    public function downloadVideoThumbnail(DownloadVideoThumbnailRequest $request): StreamedResponse
    {
        return Storage::disk(config('filesystems.cloud_disk_video_thumbnails'))
            ->download($request->getFileKey());
    }
}
