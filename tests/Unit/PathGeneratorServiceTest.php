<?php

namespace Tests\Unit;

use App\Services\PathGeneratorService;
use PHPUnit\Framework\TestCase;
use Tests\SetsProtectedProperty;

class PathGeneratorServiceTest extends TestCase
{
    use SetsProtectedProperty;

    protected PathGeneratorService $pathGeneratorServiceMock;

    protected const PATH_TO_UPLOADED_VIDEO_INPUT_FILE = '/foo/bar/hello.avi';
    protected const VIDEO_OUTPUT_TARGET_EXTENSION = 'mp4';
    protected const DRIVER_VIDEO_INPUT = 's3';
    protected const DRIVER_VIDEO_OUTPUT = 's3';
    protected const DRIVER_VIDEO_THUMBNAILS = 's3';
    protected const BUCKET_NAME_VIDEO_INPUT = 'conversion-example-input';
    protected const BUCKET_NAME_VIDEO_OUTPUT = 'conversion-example-output';
    protected const BUCKET_NAME_VIDEO_THUMBNAILS = 'conversion-example-output-thumbnails';

    protected function setUp(): void
    {
        parent::setUp();

        $this->pathGeneratorServiceMock = $this->getMockBuilder(PathGeneratorService::class)
            ->onlyMethods(['setPropertiesFromConfig'])
            ->setConstructorArgs([
                'pathToUploadedVideoInputFile' => self::PATH_TO_UPLOADED_VIDEO_INPUT_FILE,
                'videoOutputTargetExtension' => self::VIDEO_OUTPUT_TARGET_EXTENSION
             ])
            ->getMock();

        $this->setProtectedProperty($this->pathGeneratorServiceMock, 'driverVideoInput', self::DRIVER_VIDEO_INPUT);
        $this->setProtectedProperty($this->pathGeneratorServiceMock, 'driverVideoOutput', self::DRIVER_VIDEO_OUTPUT);
        $this->setProtectedProperty($this->pathGeneratorServiceMock, 'driverVideoThumbnails', self::DRIVER_VIDEO_THUMBNAILS);
        $this->setProtectedProperty($this->pathGeneratorServiceMock, 'bucketNameVideoInput', self::BUCKET_NAME_VIDEO_INPUT);
        $this->setProtectedProperty($this->pathGeneratorServiceMock, 'bucketNameVideoOutput', self::BUCKET_NAME_VIDEO_OUTPUT);
        $this->setProtectedProperty($this->pathGeneratorServiceMock, 'bucketNameVideoThumbnails', self::BUCKET_NAME_VIDEO_THUMBNAILS);
    }

    public function testGetVideoInputFilenameWithExtension()
    {
        $this->assertEquals('hello.avi', $this->pathGeneratorServiceMock->getVideoInputFilenameWithExtension());
    }

    public function testGetVideoOutputFilenameWithExtension()
    {
        $this->assertEquals('hello.mp4', $this->pathGeneratorServiceMock->getVideoOutputFilenameWithExtension());
    }

    public function testGetFullyQualifiedPathForVideoInputFilenameWithExtension()
    {
        $this->assertEquals('s3://conversion-example-input/hello.avi', $this->pathGeneratorServiceMock->getFullyQualifiedPathForVideoInputFilenameWithExtension());
    }

    public function testGetFullyQualifiedPathForVideoOutputFilename()
    {
        $this->assertEquals('s3://conversion-example-output/hello', $this->pathGeneratorServiceMock->getFullyQualifiedPathForVideoOutputFilename());
    }

    public function testGetVideoThumbnailsFolder()
    {
        $this->assertEquals('hello', $this->pathGeneratorServiceMock->getVideoThumbnailsFolder());
    }

    public function testGetFullyQualifiedPathForVideoThumbnailsFolder()
    {
        $this->assertEquals('s3://conversion-example-output-thumbnails/hello/', $this->pathGeneratorServiceMock->getFullyQualifiedPathForVideoThumbnailsFolder());
    }
}
