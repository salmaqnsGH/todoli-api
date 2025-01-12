<?php

namespace App\Http\Requests\Api;

class GetPaginatedListRequest extends BaseRequest
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
            'search' => 'sometimes|string|max:255',
            'filters' => 'sometimes|string|max:255',
            'page' => 'sometimes|integer',
            'per_page' => 'sometimes|integer',
            'sort_direction' => 'sometimes|string|in:asc,desc',
        ];
    }
}
