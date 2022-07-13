<?php

namespace Tests\Feature;

use App\Services\MediaConversionServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ConversionTest extends TestCase
{
    protected const CONVERSION_JOB_ID = '123456789-abc';

    public function testCanShowUploadForm()
    {
        $response = $this->get('/');

        $response->assertOk();

        $response->assertSeeText('Do Conversion');
    }

    public function testConversionShouldNotStartWithoutFile()
    {
        $response = $this->post('do-conversion');

        $response->assertSeeText('The file field is required');
    }

    public function testCanDoConversion()
    {
        Storage::fake(config('filesystems.cloud_disk_video_input'));

        $mediaConversionServiceMock = $this->createMock(MediaConversionServiceInterface::class);
        $mediaConversionServiceMock->expects($this->once())
            ->method('queueConversion')
            ->willReturn(self::CONVERSION_JOB_ID);

        $this->app->instance(MediaConversionServiceInterface::class, $mediaConversionServiceMock);

        $temporaryUploadFile = UploadedFile::fake()->create('foo.avi');

        $response = $this->post('do-conversion', [
            'file' => $temporaryUploadFile
        ]);

        $redirectUrl = route('get-conversion-job-status', [
            'pathToUploadedVideoInputFile' => $temporaryUploadFile->hashName(),
            'conversionJobId' => self::CONVERSION_JOB_ID
        ]);

        $response->assertLocation($redirectUrl);
    }
}
