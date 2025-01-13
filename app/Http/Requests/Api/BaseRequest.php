<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class BaseRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        $response = jsonresBadRequest($this, 'Invalid data', $validator->errors());

        throw new HttpResponseException($response);
    }

    public function getId()
    {
        return $this->route('id');
    }

    public function getProjectUserId()
    {
        return $this->route('userId');
    }

    public function getTaskId()
    {
        return $this->route('taskId');
    }

    public function getTaskCommentId()
    {
        return $this->route('commentId');
    }
}
