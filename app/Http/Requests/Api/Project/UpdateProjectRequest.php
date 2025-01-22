<?php

namespace App\Http\Requests\Api\Project;

use App\Http\Requests\Api\BaseRequest;

class UpdateProjectRequest extends BaseRequest
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
            'category_id' => 'nullable|integer|exists:project_categories,id',
            'description' => 'nullable|string|max:255',
            'user_ids' => 'sometimes|array',
            'user_ids.*' => 'exists:users,id|distinct',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',  // max 10MB per file (10240KB)
        ];
    }
}
