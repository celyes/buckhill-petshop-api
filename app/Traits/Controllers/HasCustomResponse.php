<?php

namespace App\Traits\Controllers;

trait HasCustomResponse
{
    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $extra
     * @return array<int|string, mixed>
     */
    public function success(array $data = [], array $extra = []): array
    {
        return $this->jsonResponse(
            data: $data,
            extra: $extra
        );
    }

    /**
     * @param string $error
     * @param array<int, mixed> $errors
     * @param array<int, mixed> $trace
     * @param int $code
     * @return array<int|string, mixed>
     */
    public function error(string $error, array $errors = [], array $trace = []): array
    {
        return $this->jsonResponse(
            success: false,
            error: $error,
            errors: $errors,
            trace: $trace,
        );
    }

    /**
     * @param bool $success
     * @param array<string, mixed> $data
     * @param string|null $error
     * @param array<int, mixed>|null $errors
     * @param array<string, mixed>|null $extra
     * @param array<int, mixed>|null $trace
     * @return array<string|int, mixed>
     */
    protected function jsonResponse(
        bool $success = true,
        array $data = [],
        ?string $error = null,
        ?array $errors = [],
        ?array $extra = [],
        ?array $trace = []
    ): array
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
        return $data;
    }
}
