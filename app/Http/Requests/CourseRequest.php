<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canManage() ?? false;
    }

    public function rules(): array
    {
        $course = $this->route('course');

        return [
            'training_type_id' => ['required', 'integer', 'exists:training_types,id'],
            'name' => [
                'required',
                'string',
                'max:120',
                Rule::unique('courses')
                    ->where('training_type_id', $this->integer('training_type_id'))
                    ->ignore($course),
            ],
            'workload_hours' => ['required', 'integer', 'min:1', 'max:2000'],
            'description' => ['nullable', 'string', 'max:2000'],
            'active' => ['required', 'boolean'],
        ];
    }
}
