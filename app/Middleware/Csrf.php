<?php
// Захист від Cross-Site Request Forgery атак
// при кожному запиті генеруємо токун, який зберігається в сесії
class CsrfMiddleware
{
    public static function generateToken()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    public static function validate()
    {
        $methods = array('POST', 'PUT', 'DELETE');

        if (!in_array($_SERVER['REQUEST_METHOD'], $methods, true)) {
            return;
        }

        $token = '';

        if (isset($_POST['_csrf_token'])) {
            $token = $_POST['_csrf_token'];
        } elseif (isset($_SERVER['HTTP_X_CSRF_TOKEN'])) {
            $token = $_SERVER['HTTP_X_CSRF_TOKEN'];
        }

        $sessionToken = isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : '';

        if (!hash_equals($sessionToken, $token)) {
            Response::abort(419, 'CSRF token mismatch.');
        }
    }
}