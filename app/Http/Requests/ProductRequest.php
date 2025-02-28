<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProductRequest extends FormRequest
{
  
    public function authorize()
    {
        // Check if the user is authorized to create or update a product
        // You can further customize this logic as needed
        return Auth::check();
    }

  
    public function rules()
    {
       
        $rules = [
            'name' => 'required|string|max:255',  
            'description' => 'nullable|string|max:1000', 
            'items' => 'nullable|numeric',
            'color' => 'nullable|string',
            'shape' => 'nullable|string',
            'min_order_quantity' => 'nullable|numeric',
            'max_order_quantity' => 'nullable|numeric',
            'notes' => 'nullable',
            'price' => 'required|numeric|min:0', 
            'category_id' => 'required|exists:categories,id', 
            'image' => 'nullable|image|max:2048',  
        ];

 
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['image'] = 'nullable|image|max:2048';
        }

        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => 'product name',
            'description' => 'product description',
            'price' => 'product price',
            'category_id' => 'category',
            'image' => 'product image',
        ];
    }
}
