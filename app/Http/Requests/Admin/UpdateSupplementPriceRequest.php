<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplementPriceRequest extends FormRequest
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
            'delivery_for_one_kiloMeter_cost' => 'sometimes|numeric',
            'base_panel_cost' => 'sometimes|numeric',
            'dollar_price_against_sp' => 'sometimes|numeric',
            'one_meter_of_cables_cost' => 'sometimes|numeric',
            'household_installation_cost' => 'sometimes|numeric',
            'agriculture_installation_cost' => 'sometimes|numeric',
            'industrial_installation_cost' => 'sometimes|numeric',
        ];
    }
}
