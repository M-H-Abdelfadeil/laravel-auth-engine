<?php

namespace App\Http\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ResponseService
{
    /**
     * send response success
     *
     * @param  array  $data
     * @param  string  $message
     * @return JsonResponse
     */
    public static function sendResponseSuccess($data = [], int $code = 200, $message = null)
    {
        $message = $message ? $message : __('messages.done successfully');
        $response = self::responseData(true, $code, $data, $message);

        return response()->json($response, $code);
    }

    /**
     *  Send response error
     *
     * @param  array  $data
     * @param  mixed  $message
     * @param  mixed  $code
     * @return JsonResponse
     */
    public static function sendResponseError($data, int $code, $message = null)
    {

        $response = self::responseData(false, $code, $data, $message);

        return response()->json($response, $code);
    }

    public static function sendBadRequest($message = null)
    {
        return self::sendResponseError(null, Response::HTTP_BAD_REQUEST, $message);
    }

    public static function sendNotFound($message = null)
    {
        if (! $message) {
            $message = __('messages.Not found');
        }

        return self::sendResponseError(null, Response::HTTP_NOT_FOUND, $message);
    }

    private static function responseData(bool $status, int $status_code, $data, $message)
    {

        $message = $message ? $message : Response::$statusTexts[$status_code];

        return [
            'status' => $status,
            'status_code' => $status_code,
            'data' => $data,
            'message' => $message,
        ];
    }

    /**
     * send response success
     *
     * @param  array  $data
     * @return JsonResponse
     */
    public static function sendBackResponseSuccess($route)
    {
        return response()->json(['url' => $route], 200);
        $response = self::responseData(true, 200, ['url' => $route], __('messages.done successfully'));

        return response()->json($response, 200);
    }

    public static function sendResponse($data, int $code, $message)
    {
        $response = self::responseData(true, $code, $data, $message);

        return response()->json($response, $code);
    }
}
