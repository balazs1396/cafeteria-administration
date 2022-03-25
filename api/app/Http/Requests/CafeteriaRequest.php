<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CafeteriaRequest extends FormRequest
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
            'start_month' => 'required|integer|between:1,12',
            'accounts' => 'required|array',
            'accounts.*.name' => 'required|string|exists:accounts,name',
            'accounts.*.annual_value' => 'required|integer|min:0|max:200000'
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'start_month'  => $this->startMonth,
        ]);
    }
}
