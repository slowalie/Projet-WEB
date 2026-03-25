<?php
declare(strict_types=1);

// Start session FIRST
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../router/router.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$twig = createTwigEnvironment();
$response = dispatchRoute($requestUri, $twig);
echo $response;

?>