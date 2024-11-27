<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBatteryRequest extends FormRequest
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
            'type' => 'sometimes',
            'manufacture_company' => 'sometimes',
            'model'=>'sometimes',
            'ampere'=>'sometimes',
            'volt'=>'sometimes',
            'description'=>'sometimes',
            'price'=>'sometimes',
            'quantity_available'=>'sometimes',
            'photo'=>'sometimes|file',
        ];
    }
}
 