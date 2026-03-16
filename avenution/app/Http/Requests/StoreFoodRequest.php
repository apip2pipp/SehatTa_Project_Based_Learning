<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFoodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Admin middleware handles authorization
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'category' => 'required|in:Protein Hewani,Protein Nabati,Karbohidrat,Sayuran,Buah,Dairy,Lainnya',
            'calories' => 'required|integer|min:0|max:2000',
            'protein' => 'required|numeric|min:0|max:200',
            'carbs' => 'required|numeric|min:0|max:300',
            'fat' => 'required|numeric|min:0|max:200',
            'fiber' => 'nullable|numeric|min:0|max:50',
            'sugars' => 'nullable|numeric|min:0|max:200',
            'sodium' => 'nullable|numeric|min:0|max:5000',
            'cholesterol' => 'nullable|numeric|min:0|max:500',
            'meal_type' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image_url' => 'nullable|url|max:500',
            'dietary_tags' => 'nullable|string',
            'health_benefits' => 'nullable|string',
            'emoji' => 'nullable|string|max:10',
        ];
    }

    /**
     * Prepare data for validation
     */
    protected function prepareForValidation()
    {
        // Convert comma-separated strings to arrays if needed
        if ($this->dietary_tags && is_string($this->dietary_tags)) {
            $this->merge([
                'dietary_tags' => array_map('trim', explode(',', $this->dietary_tags)),
            ]);
        }
        
        if ($this->health_benefits && is_string($this->health_benefits)) {
            $this->merge([
                'health_benefits' => array_map('trim', explode(',', $this->health_benefits)),
            ]);
        }
        
        if ($this->health_benefits && is_string($this->health_benefits)) {
            $this->merge([
                'health_benefits' => array_map('trim', explode(',', $this->health_benefits)),
            ]);
        }
    }
}
