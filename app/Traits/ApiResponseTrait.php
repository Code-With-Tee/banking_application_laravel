<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    protected function respondSuccess(array $data = [], string $message = ''): JsonResponse
    {
        return $this->apiResponse(['success' => true, 'result' => $data, 'message' => $message], 200);
    }

    protected function respondError(string $message = '', $statusCode = 400): JsonResponse
    {
        return $this->apiResponse(['success' => false, 'message' => $message], $statusCode);
    }

    protected function respondValidationError(string $message = '', $statusCode = 400): JsonResponse
    {
        return $this->apiResponse(['success' => false, 'message' => $message], $statusCode);
    }


    protected function apiResponse($data = [], $status = 400): JsonResponse
    {

        if (isset($data['exception']) && ($data['exception'] instanceof \Error || $data['exception'] instanceof \Exception)) {
            if (config('app.env') !== 'production') {
                $data['exception'] = [
                    'message' => $data['exception']->getMessage(),
                    'file' => $data['exception']->getFile(),
                    'line' => $data['exception']->getLine(),
                    'code' => $data['exception']->getCode(),
                    'trace' => $data['exception']->getTrace(),
                ];
            }
            if ($status === 200) {
                $status = 500;
            }
        }
        return response()->json($data, $status);
    }
}
