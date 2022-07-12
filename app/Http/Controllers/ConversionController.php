<?php

namespace App\Http\Controllers;

use App\Actions\QueueConversionAction;
use App\Actions\UploadFileToCloudAction;
use App\Exceptions\PathGeneratorService\FilenameNotPresentException;
use App\Http\Requests\DoConversionRequest;
use App\Http\Requests\DownloadVideoOutputRequest;
use App\Http\Requests\DownloadVideoThumbnailRequest;
use App\Services\MediaConversionServiceInterface;
use App\Services\PathGeneratorService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ConversionController extends Controller
{
    public function showUploadForm(): View
    {
        return view('upload');
    }

    /**
     * @throws FilenameNotPresentException
     */
    public function doConversion(DoConversionRequest $request, UploadFileToCloudAction $uploadFileToCloudAction, QueueConversionAction $queueConversionAction): RedirectResponse
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
     * @throws FilenameNotPresentException
     */
    public function getConversionJobStatus(string $pathToUploadedVideoInputFile, string $conversionJobId, MediaConversionServiceInterface $mediaConversionService): View
    {
        $conversionJobStatus = $mediaConversionService->getConversionJobStatus($conversionJobId);

        $isConversionJobComplete = $mediaConversionService->isConversionJobComplete($conversionJobStatus);

        if ($isConversionJobComplete) {
            $videoOutputFileKey = $this->getVideoOutputFileKey($pathToUploadedVideoInputFile, $mediaConversionService->getTargetExtension());
            $videoThumbnailsFileKeys = $this->getVideoThumbnailsFileKeys($pathToUploadedVideoInputFile, $mediaConversionService->getTargetExtension());
        }

        $viewData = $this->getViewDataForConversionJobStatus(
            $conversionJobId,
            $conversionJobStatus,
            $isConversionJobComplete,
            $videoOutputFileKey ?? false,
            $videoThumbnailsFileKeys ?? []
        );

        return view('conversion_job_status', $viewData);
    }

    /**
     * @throws FilenameNotPresentException
     */
    protected function getVideoThumbnailsFileKeys(string $pathToUploadedVideoInputFile, string $videoOutputTargetExtension): array
    {
        $pathGeneratorService = PathGeneratorService::create($pathToUploadedVideoInputFile, $videoOutputTargetExtension);

        return Storage::disk(config('filesystems.cloud_disk_video_thumbnails'))
            ->files($pathGeneratorService->getVideoThumbnailsFolder());
    }

    /**
     * @throws FilenameNotPresentException
     */
    protected function getVideoOutputFileKey(string $pathToUploadedVideoInputFile, string $videoOutputTargetExtension): string
    {
        $pathGeneratorService = PathGeneratorService::create($pathToUploadedVideoInputFile, $videoOutputTargetExtension);

        return $pathGeneratorService->getVideoOutputFilenameWithExtension();
    }

    #[ArrayShape([
        'conversionJobId' => "string",
        'conversionJobStatusToHtml' => "bool|string",
        'isConversionJobComplete' => "bool",
        'videoOutputFileKey' => "bool",
        'videoThumbnailsFileKeys' => "array"
    ])] protected function getViewDataForConversionJobStatus(string $conversionJobId, array $conversionJobStatus, bool $isConversionJobComplete, string $videoOutputFileKey, array $videoThumbnailsFileKeys): array
    {
        return [
            'conversionJobId' => $conversionJobId,
            'conversionJobStatusToHtml' => print_r($conversionJobStatus, true),
            'isConversionJobComplete' => $isConversionJobComplete,
            'videoOutputFileKey' => $videoOutputFileKey,
            'videoThumbnailsFileKeys' => $videoThumbnailsFileKeys
        ];
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
