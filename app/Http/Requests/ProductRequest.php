<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'unit_id' => ['required'],
            'category_id' => ['required'],
            'name' => ['required', 'unique:products,name'],
            'price_buy' => ['required', 'numeric'],
            'price_sell' => ['required', 'numeric'],
            'qty' => ['required', 'numeric']
        ];
    }
}
