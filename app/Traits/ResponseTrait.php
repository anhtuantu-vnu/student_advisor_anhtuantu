<?php

namespace App\Traits;
use Illuminate\Http\JsonResponse;

trait ResponseTrait
{
    /**
     * @param $data
     * @param string $message
     * @param int $status
     * @return JsonResponse
     */
    protected function successWithContent($data, string $message = '', int $status = 200) : JsonResponse
    {
        return response()->json([
           'meta'  => [
               'success' => true,
               'message' => $message
           ],
           'data' => $data,
           'errors' => null,
        ], $status);
    }


    /**
     * @param string $message
     * @param int $status
     * @return JsonResponse
     */
    protected function successWithNoContent(string $message = '', int $status = 200) : JsonResponse
    {
        return response()->json([
            'meta' => [
                'success' => true,
                'message' => $message
            ],
            'data' => null,
            'errors' => null
        ], $status);
    }


    /**
     * @param string $errorCode
     * @param string $errorMessage
     * @param int $status
     * @return JsonResponse
     */
    protected function failedWithErrors(string $errorCode = '', string $errorMessage = '', int $status = 400) : JsonResponse
    {
        return response()->json([
            'meta' => [
                'success' => false,
            ],
            'data' => null,
            'errors' => [
                'error_code' => $errorCode,
                'error_bags' => null,
                'error_message' => !empty($errorMessage) ? $errorMessage : __('message.common.error_system')
            ]
        ], $status);
    }
}
