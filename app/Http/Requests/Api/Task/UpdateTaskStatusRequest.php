<?php

namespace App\Http\Requests\Api\Task;

use App\Http\Requests\Api\BaseRequest;
use Illuminate\Validation\Rule;

class UpdateTaskStatusRequest extends BaseRequest
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
            'name' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('task_statuses', 'name')->ignore($this->getId()),
            ],
            'color' => 'sometimes|string|max:255',
        ];
    }
}
