<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/controllers/controller.php';
require_once __DIR__ . '/../src/controllers/HomeController.php';
require_once __DIR__ . '/../src/controllers/EspaceCandidatController.php';
require_once __DIR__ . '/../src/controllers/TaskController.php';
require_once __DIR__ . '/../src/controllers/OffresController.php';
require_once __DIR__ . '/../src/controllers/EspacePiloteController.php';
require_once __DIR__ . '/../src/controllers/AuthController.php';
require_once __DIR__ . '/../src/controllers/EntreprisesController.php';
require_once __DIR__ . '/../src/controllers/detailoffreController.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

function createTwigEnvironment(): Environment
{
    $loader = new FilesystemLoader(__DIR__ . '/../templates');
    $twig = new Environment($loader);
    $twig->addGlobal('is_authenticated', isset($_SESSION['is_authenticated']) && $_SESSION['is_authenticated'] === true);
    $twig->addGlobal('user_role', $_SESSION['user_role'] ?? null);
    $twig->addGlobal('user_email', $_SESSION['user_email'] ?? null);

    return $twig;
}

function normalizeRoute(string $requestUri): string
{
    $path = parse_url($requestUri, PHP_URL_PATH) ?? '/';
    $route = trim($path, '/');
    return $route === '' ? '/' : '/' . $route;
}

function dispatchRoute(string $requestUri, Environment $twig): string
{
    $route = normalizeRoute($requestUri);
    $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

    if ($route === '/auth/register' && $requestMethod === 'POST') {
        try {
            $controller = new AuthController();
            $controller->register($_POST);
        } catch (Throwable $exception) {
            header('Location: /home?auth_mode=signup&auth_status=db_error');
            exit;
        }

        return '';
    }

    if ($route === '/auth/login' && $requestMethod === 'POST') {
        try {
            $controller = new AuthController();
            $controller->login($_POST);
        } catch (Throwable $exception) {
            header('Location: /home?auth_mode=login&auth_status=db_error');
            exit;
        }

        return '';
    }

    if ($route === '/logout' && $requestMethod === 'GET') {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();

        header('Location: /home');
        exit;
    }

    if ($route === '/' || $route === '/home') {
        $controller = new HomeController($twig);
        return $controller->index();
    }

    if ($route === '/espace-candidat') {
        $controller = new EspaceCandidatController($twig);
        return $controller->index();
    }

    if ($route === '/tasks') {
        $controller = new TaskController($twig);
        return $controller->index();
    }

    if ($route === '/offres') {
        $controller = new OffresController($twig);
        return $controller->index();

    }

    if ($route === '/espace-pilote') {
        $controller = new EspacePiloteController($twig);
        return $controller->index();
    }

    if ($route === '/publish/offre') {
        $controller = new OffresController($twig);
        return $controller->publish();
    }

    if (preg_match('/^\/offre\/(\d+)\/edit$/', $route, $matches) && in_array($requestMethod, ['GET', 'POST'], true)) {
        $controller = new OffresController($twig);
        return $controller->edit((int) $matches[1]);
    }

    if (preg_match('/^\/offre\/(\d+)\/delete$/', $route, $matches) && $requestMethod === 'POST') {
        $controller = new OffresController($twig);
        return $controller->delete((int) $matches[1]);
    }

    if (preg_match('/^\/detail-offre\/(\d+)\/favorite$/', $route, $matches) && $requestMethod === 'POST') {
        $controller = new detailOffresController($twig);
        return $controller->addFavorite((int) $matches[1]);
    }

    if (preg_match('/^\/detail-offre\/(\d+)\/unfavorite$/', $route, $matches) && $requestMethod === 'POST') {
        $controller = new detailOffresController($twig);
        return $controller->removeFavorite((int) $matches[1]);
    }

    if (preg_match('/^\/detail-offre\/(\d+)$/', $route, $matches) && in_array($requestMethod, ['GET', 'POST'], true)) {
        $controller = new detailOffresController($twig);
        return $controller->index((int) $matches[1]);
    }

    if ($route === '/entreprises') {
        $controller = new EntreprisesController($twig);
        return $controller->index();
    }

    http_response_code(404);
    return '<h1>404 - Page non trouvee</h1>';
}

