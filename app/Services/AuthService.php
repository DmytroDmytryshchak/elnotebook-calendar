<?php
// Бізнес логіка аутентифікації та реєстрації
class AuthService
{
    private $userRepository;

    public function __construct($userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register($data)
    {
        $this->validateRegistration($data);

        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

        $userId = $this->userRepository->create(array(
            'name'     => trim($data['name']),
            'email'    => strtolower(trim($data['email'])),
            'password' => $hashedPassword,
        ));

        $row = $this->userRepository->findById($userId);

        return User::fromArray($row);
    }

    public function login($email, $password)
    {
        $row = $this->userRepository->findByEmail(strtolower(trim($email)));

        if ($row === null || !password_verify($password, $row['password'])) {
            throw new ValidationException(array(
                'credentials' => 'Invalid email or password.'
            ));
        }

        return User::fromArray($row);
    }

    public function startSession($user)
    {
        session_regenerate_id(true);
        $_SESSION['user_id']   = $user->getId();
        $_SESSION['user_name'] = $user->getName();
    }

    public function logout()
    {
        $_SESSION = array();
        session_destroy();
    }

    private function validateRegistration($data)
    {
        $errors = array();

        $name = isset($data['name']) ? trim($data['name']) : '';
        if (strlen($name) < 2) {
            $errors['name'] = 'Name must be at least 2 characters.';
        }

        $email = isset($data['email']) ? trim($data['email']) : '';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        } elseif ($this->userRepository->emailExists(strtolower($email))) {
            $errors['email'] = 'This email is already registered.';
        }

        $password = isset($data['password']) ? $data['password'] : '';
        if (strlen($password) < 8) {
            $errors['password'] = 'Password must be at least 8 characters.';
        }

        $confirm = isset($data['password_confirm']) ? $data['password_confirm'] : '';
        if ($password !== $confirm) {
            $errors['password_confirm'] = 'Passwords do not match.';
        }

        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
    }
}