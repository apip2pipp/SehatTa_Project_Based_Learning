<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnalyzeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Anyone can analyze (guest or auth)
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'age' => 'required|integer|min:10|max:120',
            'weight' => 'required|numeric|min:20|max:300',
            'height' => 'required|numeric|min:100|max:250',
            'gender' => 'required|in:male,female',
            'blood_pressure_systolic' => 'nullable|integer|min:70|max:250',
            'blood_pressure_diastolic' => 'nullable|integer|min:40|max:150',
            'blood_sugar' => 'nullable|numeric|min:50|max:500',
            'cholesterol' => 'nullable|numeric|min:100|max:400',
            'activity_level' => 'required|in:sedentary,light,moderate,active,very_active',
            'dietary_restriction' => 'nullable|in:none,vegetarian,vegan,gluten-free',
            'goal' => 'required|in:maintain,weight_loss,muscle_gain',
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'blood_pressure_systolic' => 'systolic blood pressure',
            'blood_pressure_diastolic' => 'diastolic blood pressure',
            'blood_sugar' => 'blood sugar level',
            'cholesterol' => 'cholesterol level',
            'activity_level' => 'activity level',
            'dietary_restriction' => 'dietary restriction',
            'goal' => 'health goal',
        ];
    }
}
