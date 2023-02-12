<?php

namespace App\Libraries;

class ResponseBase
{
    /**
     * Handle response success
     * @param array $data
     * @return json
     */
    public static function success($data, $code = 200)
    {
        $response = [];
        $response['code'] = $code;
        $response['data'] = isset($data['data']) && $data['data'] ? $data['data'] : null;
        $response['status'] = isset($data['status']) && $data['status'] ? $data['status'] : 'success';
        $response['message'] = isset($data['message']) && $data['message'] ? $data['message'] : null;

        return response()->json($response);
    }

     /**
     * Handle response success
     * @param string $message
     * @param integer $code
     * @return json
     */
    public static function error($message, $code = 400)
    {
        $response = [];
        $response['code'] = $code;
        $response['status'] = 'error';
        $response['message'] = $message;

        return response()->json($response);
    }
}