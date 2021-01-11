<?php

namespace App\Helper\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class ResponseJson
{
    /**
     * @param bool $success
     * @param string $message
     * @param int $status
     * @return JsonResponse
     */
    public function message(bool $success, string $message, int $status = 302): JsonResponse
    {
        return new JsonResponse([
            'success' => $success,
            'message' => $message
        ], $status);
    }
}