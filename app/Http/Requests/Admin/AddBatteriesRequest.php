<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AddBatteriesRequest extends FormRequest
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
            'type'=>'required',
            'manufacture_company'=>'required',
            'model'=>'required',
            'ampere'=>'required',
            'volt'=>'sometimes',
            'description'=>'sometimes',
            'price'=>'required',
            'quantity'=>'required',
            'photo'=>'required|file',
        ];
    }
}
