<?php

namespace App\Http\Requests\Api\Task;

use App\Http\Requests\Api\BaseRequest;

class UpdateTaskRequest extends BaseRequest
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
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:255',
            'objective' => 'nullable|string|max:255',
            'additional_notes' => 'nullable|string|max:255',
            'due_date' => 'sometimes|date',
            'images' => 'sometimes',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:10240',  // max 10MB per file (10240KB)
        ];
    }
}
