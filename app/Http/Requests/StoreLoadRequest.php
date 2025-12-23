<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLoadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage loads');
    }

    public function rules(): array
    {
        return [
            'reference_no' => 'required|string|max:50|unique:loads,reference_no',
            'pickup_address' => 'required|string|max:255',
            'pickup_lat' => 'nullable|numeric|between:-90,90',
            'pickup_lng' => 'nullable|numeric|between:-180,180',
            'delivery_address' => 'required|string|max:255',
            'delivery_lat' => 'nullable|numeric|between:-90,90',
            'delivery_lng' => 'nullable|numeric|between:-180,180',
        ];
    }
}
