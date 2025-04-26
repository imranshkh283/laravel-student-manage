<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'class_id' => 'required|integer',
            'roll_number' => 'required|string|max:255',
            'name' => [
                'required',
                Rule::unique('students')->where(function ($query) {
                    return $query->where('class_id', $this->class_id)
                        ->where('roll_number', $this->roll_number);
                }),
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'Student with this roll number already exists',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'name' => $this->first_name . ' ' . $this->last_name,
        ]);
    }
}
