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
    return new Environment($loader);
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

    if ($route === '/' || $route === '/home') {
        $controller = new HomeController($twig);
        return $controller->index();
    }

    if ($route === '/espace-candidat') {
        if (empty($_SESSION['is_authenticated'])) {
            header('Location: /home?auth_mode=login&auth_status=unauthorized');
            exit;
        }
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
        if (empty($_SESSION['is_authenticated']) || !in_array($_SESSION['user_role'] ?? '', ['pilote', 'superadmin'], true)) {
            header('Location: /home?auth_mode=login&auth_status=unauthorized');
            exit;
        }
        $controller = new EspacePiloteController($twig);
        return $controller->index();
    }

    if ($route === '/publish/offre') {
        $controller = new OffresController($twig);
        return $controller->publish();
    }

    if (preg_match('/^\/detail-offre\/(\d+)$/', $route, $matches)) {
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

