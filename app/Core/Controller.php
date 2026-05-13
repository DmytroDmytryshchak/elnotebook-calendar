<?php
// Базовий клас для всіх контролерів додатку
// Містить спільні допоміжні методи щоб не дублювати їх у кожному контролері окремо (принцип DRY)
class Controller
{
    protected function view($template, $data = array())
    {
        extract($data);

        $templatePath = BASE_PATH . '/views/' . $template . '.php';

        if (!file_exists($templatePath)) {
            throw new RuntimeException('View not found: ' . $template);
        }

        require $templatePath;
    }

    protected function json($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function redirect($url)
    {
        header('Location: ' . $url);
        exit;
    }

    protected function isAuthenticated()
    {
        return isset($_SESSION['user_id']);
    }

    protected function currentUserId()
    {
        if (isset($_SESSION['user_id'])) {
            return (int) $_SESSION['user_id'];
        }
        return null;
    }
}