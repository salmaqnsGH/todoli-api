<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $e)
    {
        // Check if request is API
        if ($this->isApiRequest($request)) {
            return $this->handleApiException($request, $e);
        }

        // For non-API requests, use default Laravel handling
        return parent::render($request, $e);
    }

    private function isApiRequest($request): bool
    {
        return $request->is('api/*') ||
            $request->wantsJson() ||
            $request->header('Accept') === 'application/json';
    }

    private function handleApiException($request, Throwable $e)
    {
        // If it's a validation exception, let Laravel handle it normally
        // as it will be caught by the FormRequest
        if ($e instanceof ValidationException) {
            return parent::render($request, $e);
        }

        // For 401 Unauthorized errors
        if ($e instanceof AuthenticationException) {
            return jsonresUnauthorized($request, 'Unauthorized user');
        }

        // For 403 Forbidden errors
        if ($e instanceof AuthorizationException) {
            return jsonresForbidden($request, 'Forbidden user is not allowed here');
        }

        // For 404 errors
        if ($e instanceof NotFoundHttpException) {
            return jsonresNotFound($request, 'Data not found');
        }

        // Handle Route Not Found due to unauthorized access
        if ($e instanceof RouteNotFoundException) {
            return jsonresUnauthorized($request, 'Unauthorized user is not allowed here');
        }

        // Handle Record Not Found due to unmatch data model input
        if ($e instanceof ModelNotFoundException) {
            return jsonresNotFound($request, 'Record not found');
        }

        // Handle Duplicate Entry
        if ($e instanceof UniqueConstraintViolationException) {
            return jsonresBadRequest($request, 'Duplicate entry detected. This record already exists.');
        }

        // For all other exceptions, return 500 server error
        return jsonresServerError($request, 'Something went wrong', null, $e);
    }
}
