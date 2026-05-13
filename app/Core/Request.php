<?php
// Замість прямого звернення до $_POST['key'] використовуємо $request->post('key') — так код легше тестувати і читати
class Request
{
    // Повертає HTTP поточного запиту
    public function method()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method'])) {
            return strtoupper($_POST['_method']);
        }

        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    // Повертає URI запиту без query string
    public function uri()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = rtrim($uri, '/');

        if ($uri === '') {
            return '/';
        }

        return $uri;
    }

    public function get($key, $default = null)
    {
        if (isset($_GET[$key])) {
            return $_GET[$key];
        }
        return $default;
    }

    public function post($key, $default = null)
    {
        if (isset($_POST[$key])) {
            return $_POST[$key];
        }
        return $default;
    }

    // Зчитує спочатку POST, потім GET
    public function input($key, $default = null)
    {
        $post = $this->post($key);
        if ($post !== null) {
            return $post;
        }

        $get = $this->get($key);
        if ($get !== null) {
            return $get;
        }

        return $default;
    }

    // перевіряє всі параметри
    public function all()
    {
        return array_merge($_GET, $_POST);
    }

    public function isJson()
    {
        $contentType = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
        return strpos($contentType, 'application/json') !== false;
    }

    public function json()
    {
        $body = file_get_contents('php://input');
        $data = json_decode($body, true);
        return $data !== null ? $data : array();
    }

    public function isAjax()
    {
        $requested = isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            ? $_SERVER['HTTP_X_REQUESTED_WITH']
            : '';
        return $requested === 'XMLHttpRequest';
    }
}