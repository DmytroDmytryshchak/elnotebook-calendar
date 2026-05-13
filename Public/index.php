<?php
// єдина точка входу в додаток

define('BASE_PATH', dirname(__DIR__));

function loadAppFile($filename)
{
    $path = BASE_PATH . '/app/' . $filename;
    if (file_exists($path)) {
        require_once $path;
    }
}

// Exceptions
loadAppFile('Exceptions/NotFoundException.php');
loadAppFile('Exceptions/ValidationException.php');

// Interfaces
loadAppFile('Interfaces/RepositoryInterface.php');

// Core
loadAppFile('Core/Database.php');
loadAppFile('Core/Request.php');
loadAppFile('Core/Response.php');
loadAppFile('Core/Controller.php');
loadAppFile('Core/Router.php');

// Middleware
loadAppFile('Middleware/Auth.php');
loadAppFile('Middleware/Csrf.php');

// Models
loadAppFile('Models/User.php');

// Repositories
loadAppFile('Repositories/UserRepository.php');

// Services
loadAppFile('Services/AuthService.php');

// Controllers
loadAppFile('Controllers/AuthController.php');

// Bootstrap
$config = require BASE_PATH . '/config/app.php';

date_default_timezone_set($config['timezone']);

ini_set('display_errors', $config['debug'] ? '1' : '0');
error_reporting($config['debug'] ? E_ALL : 0);

session_start();

// обробка request
$request = new Request();
$router  = new Router();

CsrfMiddleware::generateToken();
CsrfMiddleware::validate();

require BASE_PATH . '/routes/web.php';

try {
    $router->dispatch($request);
} catch (NotFoundException $e) {
    Response::abort(404, '404 — Page not found.');
} catch (Exception $e) {
    if ($config['debug']) {
        echo '<pre>' . htmlspecialchars($e->getMessage()) . "\n" . $e->getTraceAsString() . '</pre>';
    } else {
        Response::abort(500, '500 — Internal Server Error.');
    }
}