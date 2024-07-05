<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $extra
     * @param int $code
     * @return JsonResponse
     */
    public function success(array $data = [], array $extra = [], int $code = 200): JsonResponse
    {
        // make sure the code indicates a success indeed...
        if ($code < 200 || $code > 299 ) {
            $code = 200;
        }
        return $this->jsonResponse(
            data: $data,
            extra: $extra,
            code: $code
        );
    }

    /**
     * @param string $error
     * @param array<int, mixed> $errors
     * @param array<int, mixed> $trace
     * @param int $code
     * @return JsonResponse
     */
    public function error(string $error, array $errors = [], array $trace = [], int $code = 400): JsonResponse
    {
        return $this->jsonResponse(
            success: false,
            error: $error,
            errors: $errors,
            trace: $trace,
            code: $code
        );
    }

    /**
     * @param bool $success
     * @param array<string, mixed> $data
     * @param string|null $error
     * @param array<int, mixed>|null $errors
     * @param array<string, mixed>|null $extra
     * @param array<int, mixed>|null $trace
     * @param int $code
     * @return JsonResponse
     */
    protected function jsonResponse(
        bool $success = true,
        array $data = [],
        ?string $error = null,
        ?array $errors = [],
        ?array $extra = [],
        ?array $trace = [],
        int $code = 200
    ): JsonResponse
    {
        $data = [
            'success' => $success,
            'data' => $data,
            'error' => $error,
            'errors' => $errors
        ];
        if (!is_null($error)) {
            $data['trace'] = $trace;
        } else {
            $data['extra'] = $extra;
        }
        return response()->json($data, $code);
    }

}
