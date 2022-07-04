<?php

namespace App\Providers;

use App;
use App\Services\AmazonWebServices\ElementalMediaConvertService;
use App\Services\MediaConversionServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        App::bind(MediaConversionServiceInterface::class, ElementalMediaConvertService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
