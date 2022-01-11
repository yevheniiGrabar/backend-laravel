<?php

use Illuminate\Http\JsonResponse;

if (!function_exists('response_json')) {

    function response_json($data = [], $code = 200, $options = 0, $headers = []): JsonResponse
    {
        $response = [
            'meta' => [
                'code' => $code
            ],
            'data' => $data
        ];

        return new JsonResponse($response, $code, $headers, $options);
    }
}

if (!function_exists('response_json_error')) {

    function response_json_error($type, $message, $description = '', $code = 500): JsonResponse
    {
        $response = [
            'meta' => [
                'error_type' => $type,
                'code' => $code,
                'error_message' => $message,
                'error_description' => $description,
            ],
        ];

        return new JsonResponse($response, $code);
    }
}
