<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;

class DoConversionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file' => 'required',
        ];
    }

    public function getUploadedFile(): UploadedFile
    {
        $validated = $this->validated();

        $uploadedFile = $validated['file'] ?? null;

        if (!$uploadedFile) {
            throw new UploadException('File is not present');
        }

        /** @var UploadedFile $uploadedFile */
        if (!$uploadedFile->isValid()) {
            throw new UploadException('Uploaded file is not valid!');
        }

        return $uploadedFile;
    }
}
