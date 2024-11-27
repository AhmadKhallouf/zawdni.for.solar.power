<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AddDamagesRequest extends FormRequest
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
            'type_of_inverter' => 'required',
            'manufacture_company' => 'required',
            'model_of_inverter' => 'required',
            'watt' => 'required',
            'code' => 'required',
            'description' => 'required',
            'solution' => 'required',
        ];
    }
}
