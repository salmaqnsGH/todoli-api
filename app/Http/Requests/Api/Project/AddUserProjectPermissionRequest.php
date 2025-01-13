<?php

namespace App\Http\Requests\Api\Project;

use App\Http\Requests\Api\BaseRequest;

class AddUserProjectPermissionRequest extends BaseRequest
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
            'project_id' => 'required|integer|exists:projects,id',
            'user_id' => 'required|integer|exists:users,id',
            'permission_id' => 'required|integer|exists:permissions,id',
        ];
    }
}
