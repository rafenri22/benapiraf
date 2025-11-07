<?php
// Helper untuk response JSON standar

class ApiResponse
{
    public static function success($data, $message = 'Success', $code = 200)
    {
        http_response_code($code);
        return json_encode([
            'status' => true,
            'message' => $message,
            'data' => $data
        ]);
    }

    public static function error($message = 'Error', $code = 400)
    {
        http_response_code($code);
        return json_encode([
            'status' => false,
            'message' => $message,
            'data' => null
        ]);
    }
}
?>