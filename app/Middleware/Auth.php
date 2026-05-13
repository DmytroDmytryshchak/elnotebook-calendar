<?php
// Захищає маршрути від неавторизованого доступу
// виулиувється на початку конструкторів, де вимагається авторизація
class AuthMiddleware
{
    public static function handle()
    {
        if (!isset($_SESSION['user_id'])) {
            Response::redirect('/login');
        }
    }
}