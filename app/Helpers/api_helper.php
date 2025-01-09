<?php

use App\Constants\HttpCode;
use App\Http\Responses\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

function jsonres(?Request $request, int $httpCode, bool $success, ?string $message = null, $data = null, ?\Exception $e = null): JsonResponse
{
    $requestForLogging = null;
    if (isset($request)) {
        $clonedRequest = clone $request;
        $clonedRequest->headers->remove('Authorization');
        $requestContent = json_decode($clonedRequest->getContent(), true);
        if (isset($requestContent['access_token'])) {
            unset($requestContent['access_token']);
        }
        $jsonRequestContent = json_encode($requestContent, JSON_PRETTY_PRINT);

        // Get client IP
        $clientIp = $request->ip();

        // Get additional client information
        $userAgent = $request->header('User-Agent');
        $referer = $request->header('Referer');
        $requestTime = date('Y-m-d H:i:s');

        $additionalInfo = collect([
            'Client IP' => $clientIp,
            'Request Time' => $requestTime,
            'User Agent' => $userAgent,
            'Referer' => $referer,
        ])->map(function ($value, $key) {
            return str_pad($key.':', 18).' '.$value;
        })->implode("\n");

        $requestForLogging = sprintf(
            "%s %s %s\n%s\n\n%s\n\n%s",
            $clonedRequest->getMethod(),
            $clonedRequest->getRequestUri(),
            $clonedRequest->getProtocolVersion(),
            collect($clonedRequest->headers->all())->map(function ($header, $key) {
                return str_pad(ucfirst($key).':', 18).' '.implode(', ', $header);
            })->implode("\n"),
            $additionalInfo,
            $jsonRequestContent
        );
    }

    $serverTime = Carbon::now()->toISOString();
    $response = new ApiResponse($serverTime, $success, $message, $data);
    $jsonResponse = response()->json($response, $httpCode);
    $responseDataForLogging = $jsonResponse->getData(true);
    if (isset($responseDataForLogging['data'])) {
        unset($responseDataForLogging['data']);
    }

    Log::info('[START] app/Helpers/api_helpers/jsonres()');
    Log::info('Request: '.$requestForLogging);
    if (! $success) {
        Log::info('Error data: '.json_encode($data, JSON_PRETTY_PRINT));
    }
    Log::info('Response: '.json_encode($responseDataForLogging));
    if ($e != null) {
        Log::error('Exception: '.$e);
    }
    Log::info('[END] app/Helpers/api_helpers/jsonres()');

    return $jsonResponse;
}

if (! function_exists('jsonresSuccess')) {
    function jsonresSuccess(?Request $request, ?string $message = null, $data = null)
    {
        return jsonres($request, HttpCode::SUCCESS, true, $message, $data);
    }
}

if (! function_exists('jsonresCreated')) {
    function jsonresCreated(?Request $request, ?string $message = null, $data = null)
    {
        return jsonres($request, HttpCode::CREATED, true, $message, $data);
    }
}

if (! function_exists('jsonresBadRequest')) {
    function jsonresBadRequest(?Request $request, ?string $message = null, $data = null, ?\Exception $e = null)
    {
        return jsonres($request, HttpCode::BAD_REQUEST, false, $message, $data, $e);
    }
}

if (! function_exists('jsonresUnauthorized')) {
    function jsonresUnauthorized(?Request $request, ?string $message = null, $data = null, ?\Exception $e = null)
    {
        return jsonres($request, HttpCode::UNAUTHORIZED, false, $message, $data, $e);
    }
}

if (! function_exists('jsonresForbidden')) {
    function jsonresForbidden(?Request $request, ?string $message = null, $data = null, ?\Exception $e = null)
    {
        return jsonres($request, HttpCode::FORBIDDEN, false, $message, $data, $e);
    }
}

if (! function_exists('jsonresNotFound')) {
    function jsonresNotFound(?Request $request, ?string $message = null, $data = null, ?\Exception $e = null)
    {
        return jsonres($request, HttpCode::NOT_FOUND, false, $message, $data, $e);
    }
}

if (! function_exists('jsonresServerError')) {
    function jsonresServerError(?Request $request, ?string $message = null, $data = null, ?\Exception $e = null)
    {
        return jsonres($request, HttpCode::INTERNAL_ERROR, false, $message, $data, $e);
    }
}

if (! function_exists('jsonresHttpError')) {
    function jsonresHttpError(?Request $request, int $httpCode, ?string $message = null, $data = null, ?\Exception $e = null)
    {
        return jsonres($request, $httpCode, false, $message, $data, $e);
    }
}

/*
 * Messages
 */
if (! function_exists('internalErrorMessage')) {
    function internalErrorMessage(string $actionMessage): string
    {
        return "Failed to {$actionMessage} due to an internal error";
    }
}
