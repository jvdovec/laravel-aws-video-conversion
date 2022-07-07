<?php

namespace App\Types\AmazonWebServices;

class ConversionSettingsType
{
    public const VIDEO_WIDTH = 1280;

    public const VIDEO_HEIGHT = 720;

    /*
     * Extension used for generating paths
     *
     * Make sure that extension relates to conversion settings container
     */
    public const EXTENSION = 'mp4';

    /*
     * Default conversion settings for video conversion & thumbnail generation
     *
     * Some of the keys are as null, will be set later
     */
    protected array $conversionSettings = [
        'OutputGroups' => [
            /*
             * Video
             *
             * If you change order of this OutputGroup then you should change index of it in
             * $this->setVideosDestination() method
             */
            [
                'Name' => 'Video',
                'OutputGroupSettings' => [
                    'Type' => 'FILE_GROUP_SETTINGS',
                    'FileGroupSettings' => [
                        'Destination' => null,
                    ],
                ],
                'Outputs' => [
                    [
                        'VideoDescription' => [
                            'AfdSignaling' => 'NONE',
                            'AntiAlias' => 'ENABLED',
                            'CodecSettings' => [
                                'Codec' => 'H_264',
                                'H264Settings' => [
                                    'AdaptiveQuantization' => 'HIGH',
                                    'Bitrate' => 2200000,
                                    'CodecLevel' => 'LEVEL_3_1',
                                    'CodecProfile' => 'MAIN',
                                    'EntropyEncoding' => 'CAVLC',
                                    'FlickerAdaptiveQuantization' => 'DISABLED',
                                    'FramerateControl' => 'SPECIFIED',
                                    'FramerateConversionAlgorithm' => 'DUPLICATE_DROP',
                                    'FramerateDenominator' => 1,
                                    'FramerateNumerator' => 30,
                                    'GopBReference' => 'DISABLED',
                                    'GopClosedCadence' => 1,
                                    'GopSize' => 90,
                                    'GopSizeUnits' => 'FRAMES',
                                    'HrdBufferInitialFillPercentage' => 90,
                                    'InterlaceMode' => 'PROGRESSIVE',
                                    'MinIInterval' => 0,
                                    'NumberBFramesBetweenReferenceFrames' => 0,
                                    'NumberReferenceFrames' => 3,
                                    'ParControl' => 'INITIALIZE_FROM_SOURCE',
                                    'QualityTuningLevel' => 'SINGLE_PASS',
                                    'RateControlMode' => 'CBR',
                                    'RepeatPps' => 'DISABLED',
                                    'SceneChangeDetect' => 'ENABLED',
                                    'Slices' => 1,
                                    'SlowPal' => 'DISABLED',
                                    'Softness' => 0,
                                    'SpatialAdaptiveQuantization' => 'ENABLED',
                                    'Syntax' => 'DEFAULT',
                                    'Telecine' => 'NONE',
                                    'TemporalAdaptiveQuantization' => 'ENABLED',
                                    'UnregisteredSeiTimecode' => 'DISABLED',
                                ],
                            ],
                            'ColorMetadata' => 'INSERT',
                            'Height' => self::VIDEO_HEIGHT,
                            'RespondToAfd' => 'NONE',
                            'ScalingBehavior' => 'DEFAULT',
                            'Sharpness' => 100,
                            'TimecodeInsertion' => 'DISABLED',
                            'Width' => self::VIDEO_WIDTH,
                        ],
                        'AudioDescriptions' => [
                            [
                                'AudioSourceName' => 'Audio Selector 1',
                                'AudioTypeControl' => 'FOLLOW_INPUT',
                                'CodecSettings' => [
                                    'Codec' => 'AAC',
                                    'AacSettings' => [
                                        'AudioDescriptionBroadcasterMix' => 'NORMAL',
                                        'Bitrate' => 128000,
                                        'CodecProfile' => 'LC',
                                        'CodingMode' => 'CODING_MODE_2_0',
                                        'RateControlMode' => 'CBR',
                                        'RawFormat' => 'NONE',
                                        'SampleRate' => 44100,
                                        'Specification' => 'MPEG4',
                                    ],
                                ],
                                'LanguageCodeControl' => 'FOLLOW_INPUT',
                            ],
                        ],
                        'ContainerSettings' => [
                            'Container' => 'MP4',
                            'Mp4Settings' => [
                                'CslgAtom' => 'INCLUDE',
                                'FreeSpaceBox' => 'EXCLUDE',
                                'MoovPlacement' => 'PROGRESSIVE_DOWNLOAD',
                            ],
                        ],
                    ],
                ],
            ],

            /*
             * Thumbnail
             *
             * If you change order of this OutputGroup then you should change index of it in
             * $this->setThumbnailsDestination() method
             */
            [
                'Name' => 'Thumbnail',
                'OutputGroupSettings' => [
                    'Type' => 'FILE_GROUP_SETTINGS',
                    'FileGroupSettings' => [
                        'Destination' => null,
                    ],
                ],
                'Outputs' => [
                    [
                        'VideoDescription' => [
                            'CodecSettings' => [
                                'Codec' => 'FRAME_CAPTURE',
                                'FrameCaptureSettings' => [
                                    // Frame capture will encode the first frame of the output stream, then one frame every framerateDenominator/framerateNumerator seconds.
                                    'FramerateNumerator' => 1,
                                    // Frame capture will encode the first frame of the output stream, then one frame every framerateDenominator/framerateNumerator seconds.
                                    'FramerateDenominator' => 5,
                                    /*
                                         * By default MediaConvert will always store first frame of the video,
                                         * and this cant be changed. So if we want also something else than (mostly)
                                         * empty thumbnail then MaxCaptures should be at least 2.
                                         *
                                         */
                                    'MaxCaptures' => 2,
                                    'Quality' => 80,
                                ],
                            ],
                            'DropFrameTimecode' => 'ENABLED',
                            'RespondToAfd' => 'NONE',
                            'Sharpness' => 50,
                            'AntiAlias' => 'ENABLED',
                            'AfdSignaling' => 'NONE',
                            'Width' => 1280,
                            'ScalingBehavior' => 'DEFAULT',
                            'ColorMetadata' => 'INSERT',
                            'Height' => 720,
                            'TimecodeInsertion' => 'DISABLED',
                        ],
                        'ContainerSettings' => [
                            'Container' => 'RAW',
                        ],
                    ],
                ],
            ],
        ],
        'AdAvailOffset' => 0,
        'Inputs' => [
            [
                'AudioSelectors' => [
                    'Audio Selector 1' => [
                        'Offset' => 0,
                        'DefaultSelection' => 'NOT_DEFAULT',
                        'ProgramSelection' => 1,
                        'SelectorType' => 'TRACK',
                    ],
                ],
                'VideoSelector' => [
                    'ColorSpace' => 'FOLLOW',
                ],
                'FilterEnable' => 'AUTO',
                'PsiControl' => 'USE_PSI',
                'FilterStrength' => 0,
                'DeblockFilter' => 'DISABLED',
                'DenoiseFilter' => 'DISABLED',
                'TimecodeSource' => 'EMBEDDED',
                'FileInput' => null,
            ],
        ],
        'TimecodeConfig' => [
            'Source' => 'EMBEDDED',
        ],
    ];

    public function __construct(string $videoInputFullyQualifiedPath, string $videoOutputFullyQualifiedPath, string $videoThumbnailsFullyQualifiedPath)
    {
        // example: s3://input-bucket/source.mkv
        $this->conversionSettings['Inputs'][0]['FileInput'] = $videoInputFullyQualifiedPath;

        // example: s3://thumb-output-bucket/target - extension will be added automatically
        $this->conversionSettings['OutputGroups'][0]['OutputGroupSettings']['FileGroupSettings']['Destination'] = $videoOutputFullyQualifiedPath;

        // example: s3://thumb-output-bucket/folder-for-thumbnails/
        $this->conversionSettings['OutputGroups'][1]['OutputGroupSettings']['FileGroupSettings']['Destination'] = $videoThumbnailsFullyQualifiedPath;
    }

    public function getAll(): array
    {
        return $this->conversionSettings;
    }
}
