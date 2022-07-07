<?php

namespace App\Services\AmazonWebServices;

use App\Exceptions\WithAdditionalDataException;
use App\Services\MediaConversionServiceInterface;
use App\Types\AmazonWebServices\ConversionSettingsType;
use Aws\MediaConvert\MediaConvertClient;
use Aws\Result;
use Exception;
use Illuminate\Support\Facades\Cache;

class ElementalMediaConvertService implements MediaConversionServiceInterface
{
    protected ?MediaConvertClient $client;

    /**
     * @throws WithAdditionalDataException
     */
    public function __construct()
    {
        $singleEndpointUrl = $this->getSingleEndpointUrl();

        try {
            $this->client = new MediaConvertClient([
                'version' => config('aws.mediaconvert.client_version'),
                'region' => config('aws.mediaconvert.region'),
                'endpoint' => $singleEndpointUrl,
            ]);
        } catch (Exception $e) {
            throw new WithAdditionalDataException('Problem with initialising convert client', ['exception' => $e]);
        }
    }

    /**
     * @throws WithAdditionalDataException
     */
    protected function getSingleEndpointUrl()
    {
        $cacheKey = 'aws_mediaconvert_endpoint';

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $client = new MediaConvertClient([
                'version' => config('aws.mediaconvert.client_version'),
                'region' => config('aws.mediaconvert.region'),
            ]);

            $result = $client->describeEndpoints([]);
            $singleEndpointUrl = $result['Endpoints'][0]['Url'];

            // Cache endpoint url for approx. one month
            Cache::put($cacheKey, $singleEndpointUrl, 86400 * 31);

            return $singleEndpointUrl;
        } catch (Exception $e) {
            throw new WithAdditionalDataException('Problem with getting single endpoint', ['exception' => $e]);
        }
    }

    /**
     * @throws WithAdditionalDataException
     */
    protected function createJob(ConversionSettingsType $conversionSettings): Result
    {
        $parameters = [
            'Role' => config('aws.mediaconvert.iam_role_arn'),
            'Settings' => $conversionSettings->getAll(),
            'Queue' => config('aws.mediaconvert.queue_arn'),
            'UserMetadata' => [
                'Customer' => 'Amazon',
            ],
        ];

        try {
            return $this->client->createJob($parameters);
        } catch (Exception $e) {
            throw new WithAdditionalDataException('There was problem with creating job', ['exception' => $e]);
        }
    }

    /**
     * @throws WithAdditionalDataException
     */
    public function queueConversion(
        string $videoInputFullyQualifiedPathWithExtension,
        string $videoOutputFullyQualifiedPathWithoutExtension,
        string $videoThumbnailsFullyQualifiedPath
    ): string
    {
        $conversionSettings = new ConversionSettingsType($videoInputFullyQualifiedPathWithExtension, $videoOutputFullyQualifiedPathWithoutExtension, $videoThumbnailsFullyQualifiedPath);

        $response = $this->createJob($conversionSettings);

        $jobData = $response->get('Job');

        if (!isset($jobData['Id'])) {
            throw new WithAdditionalDataException('Could not get ID of the job', ['response' => $response]);
        }

        return $jobData['Id'];
    }

    /**
     * @throws Exception
     */
    public function getConversionJobStatus(string $conversionJobId): array
    {
        $response = $this->client->getJob(['Id' => $conversionJobId]);
        if (!$response) {
            throw new Exception('Get no response');
        }

        return $response->get('Job');
    }

    public function isConversionJobComplete(array $conversionJobStatus): bool
    {
        return isset($conversionJobStatus['Status']) && $conversionJobStatus['Status'] === 'COMPLETE';
    }

    public function getTargetExtension(): string
    {
        return ConversionSettingsType::EXTENSION;
    }
}
