<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AddPanelsRequest extends FormRequest
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

            'type' => 'required',
            'manufacture_company' => 'required',
            'model' => 'required',
            'watt' => 'required',
            'width' => 'required|max:3|min:2',
            'hight' => 'required|max:3|min:2',
            'description' => 'required',
            'price' => 'required',
            'quantity' => 'required',
            'photo' => 'required|file',

        ];
    }
}
