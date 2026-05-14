<?php
// Обробляє HTTP запити пов'язані з автентифікацією
// без бізнес логіки, тіьки отримує дані, викликає сервіс, відповідає
class AuthController extends Controller
{
    private $authService;

    public function __construct()
    {
        $this->authService = new AuthService(new UserRepository());
    }

    public function showLogin($request)
    {
        if ($this->isAuthenticated()) {
            $this->redirect('/final_project/Public/calendar');
        }

        $errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : array();
        $old    = isset($_SESSION['old'])    ? $_SESSION['old']    : array();

        unset($_SESSION['errors'], $_SESSION['old']);

        $this->view('auth/login', array(
            'errors' => $errors,
            'old'    => $old,
        ));
    }

    public function login($request)
    {
        try {
            $user = $this->authService->login(
                $request->post('email', ''),
                $request->post('password', '')
            );

            $this->authService->startSession($user);
            $this->redirect('/final_project/Public/calendar');

        } catch (ValidationException $e) {
            $_SESSION['errors'] = $e->errors();
            $_SESSION['old']    = array('email' => $request->post('email', ''));
            $this->redirect('/final_project/Public/login');
        }
    }

    public function showRegister($request)
    {
        if ($this->isAuthenticated()) {
            $this->redirect('/final_project/Public/calendar');
        }

        $errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : array();
        $old    = isset($_SESSION['old'])    ? $_SESSION['old']    : array();

        unset($_SESSION['errors'], $_SESSION['old']);

        $this->view('auth/register', array(
            'errors' => $errors,
            'old'    => $old,
        ));
    }

    public function register($request)
    {
        try {
            $user = $this->authService->register($request->all());
            $this->authService->startSession($user);
            $this->redirect('/final_project/Public/calendar');

        } catch (ValidationException $e) {
            $_SESSION['errors'] = $e->errors();
            $_SESSION['old']    = array(
                'name'  => $request->post('name', ''),
                'email' => $request->post('email', ''),
            );
            $this->redirect('/final_project/Public/register');
        }
    }

    public function logout($request)
    {
        $this->authService->logout();
        $this->redirect('/final_project/Public/login');
    }
}