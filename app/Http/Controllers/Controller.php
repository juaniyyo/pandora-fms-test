<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Send a response with data and an optional message.
     *
     * @param mixed $data The data to be included in the response.
     * @param bool $message Optional. A message to be included in the response.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response containing the success status, data, and an optional message.
     */
    public function success($data, $message = false): JsonResponse
    {
    	$response = [
            'success' => true,
            'data'    => $data ?? [],
        ];

        if (!empty($message)) {
            $response['message'] = $message;
        }

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Send an error response with data and an optional message.
     *
     * @param mixed $error The error message to be included in the response.
     * @param array $errorMessages Optional. Additional error messages to be included in the response.
     * @param int $code Optional. The HTTP status code for the response. Defaults to 500 (Internal Server Error).
     * @param mixed $errorLog Optional. An instance of a custom error log class, if available.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response containing the error status, error message, and optional error messages.
     */
    public function error($error, $errorMessages = [], $code = Response::HTTP_INTERNAL_SERVER_ERROR, $errorLog = null): JsonResponse
    {
        if ($errorLog) {
            Log::error($errorLog->getMessage());
        }

    	$response = [
            'success' => false,
            'message' => $error ?? [],
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
