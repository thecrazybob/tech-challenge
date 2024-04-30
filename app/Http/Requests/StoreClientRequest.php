<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:190',
            'email' => 'nullable|email|max:255|required_without:phone',
            'phone' => 'nullable|regex:/^[+]?[\d\s]+$/|required_without:email',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'postcode' => 'nullable|string',
        ];
    }
}
