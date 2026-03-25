<?php
declare(strict_types=1);

// Start session FIRST
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

require_once __DIR__ . '/vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader);

// Render just the connexion_inscription modal
echo $twig->render('connexion_inscription.twig.html');
?>
