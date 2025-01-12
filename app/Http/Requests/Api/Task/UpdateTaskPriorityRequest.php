<?php

namespace App\Http\Requests\Api\Task;

use App\Http\Requests\Api\BaseRequest;

class UpdateTaskPriorityRequest extends BaseRequest
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
            'color' => 'sometimes|string|max:255',
        ];
    }
}
