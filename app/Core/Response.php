<?php
// Статичні "помагатори" для формування HTTP-відповідей
class Response
{
    public static function redirect($url, $status = 302)
    {
        http_response_code($status);
        header('Location: ' . $url);
        exit;
    }

    public static function json($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($data);
        exit;
    }

    public static function abort($status, $message = '')
    {
        http_response_code($status);
        echo $message;
        exit;
    }
}