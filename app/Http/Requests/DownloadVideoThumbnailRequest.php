<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DownloadVideoThumbnailRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'fileKey' => 'required',
        ];
    }

    public function getFileKey()
    {
        $validated = $this->validated();

        return $validated['fileKey'] ?? null;
    }
}
