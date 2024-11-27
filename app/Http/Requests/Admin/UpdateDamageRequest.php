<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDamageRequest extends FormRequest
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
            'type_of_inverter' => 'sometimes',
            'manufacture_company' => 'sometimes',
            'model_of_inverter' => 'sometimes',
            'watt' => 'sometimes',
            'code' => 'sometimes',
            'description' => 'sometimes',
            'solution' => 'sometimes',
        ];
    }
}
