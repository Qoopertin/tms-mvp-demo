<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('upload documents');
    }

    public function rules(): array
    {
        return [
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:20480', // 20MB
            'type' => 'required|in:POD,PHOTO,OTHER',
        ];
    }

    public function messages(): array
    {
        return [
            'document.max' => 'The document must not be larger than 20MB.',
            'document.mimes' => 'The document must be a PDF, JPG, JPEG, or PNG file.',
        ];
    }
}
