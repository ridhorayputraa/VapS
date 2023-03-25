<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VapeRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            //Tambahkan validasi nya sesuai field
            'name' => 'required|max:255',
            'picturePath' => 'required|image|max:5028',
            'spesification' => 'required',
            'price' => 'required| integer',
            'rate' => 'required|numeric',
            // Karna tidak ingin menambhkan validasi di types
            'types' => ''
        ];
    }
}
