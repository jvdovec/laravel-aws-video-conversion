<?php

namespace App\Http\Controllers;

use App\Http\Requests\DoConversionRequest;
use App\Http\Requests\DownloadVideoOutputRequest;
use App\Http\Requests\DownloadVideoThumbnailRequest;
use App\Services\MediaConversionServiceInterface;
use App\Services\PathGeneratorService;
use Exception;
use Illuminate\Support\Facades\Storage;

class ConversionController extends Controller
{

    public function showUploadForm()
    {
        return view('upload');
    }

    public function doConversion(DoConversionRequest $request, MediaConversionServiceInterface $mediaConversionService)
    {
        try {

            /*
             * 1. UPLOAD TO CLOUD
             */

            $cloudDiskVideoInput = Storage::disk(config('filesystems.cloud_disk_video_input'));
            $pathToUploadedVideoInputFile = $cloudDiskVideoInput->put('', $request->getUploadedFile());


            /*
             * 2. QUEUE CONVERSION
             */

            $pathGeneratorService = new PathGeneratorService(
                $pathToUploadedVideoInputFile,
                $mediaConversionService->getTargetExtension()
            );
            $conversionJobId = $mediaConversionService->queueConversion(
                $pathGeneratorService->getFullyQualifiedPathForVideoInputWithExtension(),
                $pathGeneratorService->getFullyQualifiedPathForVideoOutputWithoutExtension(),
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
                        'pathToUploadedVideoInputFile' => $pathToUploadedVideoInputFile
                    ]
                )
            );


        } catch (Exception $e) {

            return view('exception', [
                'exception' => $e->getMessage()
            ]);

        }
    }

    public function getConversionJobStatus(string $pathToUploadedVideoInputFile, string $conversionJobId, MediaConversionServiceInterface $mediaConversionService)
    {
        try {

            $videoOutputFileKey = false;
            $videoThumbnailsFileKeys = [];

            $conversionJobStatus = $mediaConversionService->getConversionJobStatus($conversionJobId);
            $conversionJobStatusToHtml = print_r($conversionJobStatus, true);
            $isConversionJobComplete = $mediaConversionService->isConversionJobComplete($conversionJobStatus);

            if ($isConversionJobComplete) {
                $pathGeneratorService = new PathGeneratorService($pathToUploadedVideoInputFile, $mediaConversionService->getTargetExtension());
                $videoOutputFileKey = $pathGeneratorService->getVideoOutputWithExtension();
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

        } catch (Exception $e) {

            return view('exception', [
                'exception' => $e->getMessage()
            ]);

        }
    }

    public function downloadVideoOutput(DownloadVideoOutputRequest $request)
    {
        try {

            return Storage::disk(config('filesystems.cloud_disk_video_output'))->download($request->getFileKey());

        } catch (Exception $e) {

            return view('exception', [
                'exception' => $e->getMessage()
            ]);

        }
    }

    public function downloadVideoThumbnail(DownloadVideoThumbnailRequest $request)
    {
        try {

            return Storage::disk(config('filesystems.cloud_disk_video_thumbnails'))->download($request->getFileKey());

        } catch (Exception $e) {

            return view('exception', [
                'exception' => $e->getMessage()
            ]);

        }
    }

}
