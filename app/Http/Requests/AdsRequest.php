<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdsRequest extends FormRequest
{
    public function authorize()
    {
        // return auth()->user()->isAdmin(); // Ensure only admins can create/update ads
    }

    public function rules(): array
    {
        return [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type' => 'required|in:product,category,zone',
            'reference_id' => 'nullable|integer',
            'zone' => 'nullable|in:Zone 1,Zone 2',
            'active' => 'boolean',
        ];
    }
}
