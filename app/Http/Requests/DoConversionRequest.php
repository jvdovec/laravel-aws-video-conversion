<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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

    public function getUploadedFile()
    {
        $validated = $this->validated();

        return $validated['file'] ?? null;
    }
}
