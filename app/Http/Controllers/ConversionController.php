<?php

namespace App\Http\Controllers;

use App\Http\Requests\DoConversionRequest;

class ConversionController extends Controller
{

    public function showUploadForm()
    {
        return view('upload');
    }

    public function doConversion(DoConversionRequest $request)
    {
        dd($request->getUploadedFile());
    }
}
