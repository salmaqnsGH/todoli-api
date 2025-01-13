<?php

namespace App\Http\Requests\Api\Project;

use App\Http\Requests\Api\BaseRequest;

class CreateProjectRequest extends BaseRequest
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'user_ids' => 'sometimes|array',
            'user_ids.*' => 'exists:users,id|distinct',
        ];
    }
}
