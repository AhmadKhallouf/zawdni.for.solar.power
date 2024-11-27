<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'first_name'=>'sometimes|max:50',
            'last_name'=>'sometimes|max:50',
            'address'=>'sometimes',
            'phone' => 'sometimes',
            'gender'=>'sometimes|max:20',
        ];
    }
}
