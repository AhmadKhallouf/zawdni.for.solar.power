<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class GetDamagesInformationToSuggestRequest extends FormRequest
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
            'type_of_inverter' => 'required|string',
            'manufacture_company' => 'sometimes|string',
            'model_of_inverter' => 'sometimes|string',
            'watt' => 'sometimes|integer',
            'code' => 'sometimes|string',
        ];
    }
}
