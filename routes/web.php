<?php

use App\Http\Controllers\ConversionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [ConversionController::class, 'showUploadForm'])->name('show-upload-form');
Route::post('do-conversion', [ConversionController::class, 'doConversion'])->name('do-conversion');
Route::get('get-conversion-job-status/{pathToUploadedVideoInputFile}/{conversionJobId}', [ConversionController::class, 'getConversionJobStatus'])->name('get-conversion-job-status');
Route::post('download-video-output', [ConversionController::class, 'downloadVideoOutput'])->name('download-video-output');
Route::post('download-video-thumbnail', [ConversionController::class, 'downloadVideoThumbnail'])->name('download-video-thumbnail');
