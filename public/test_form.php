<?php
declare(strict_types=1);

// Start session FIRST
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

require_once __DIR__ . '/../vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader(__DIR__ . '/../templates');
$twig = new Environment($loader);

// Render just the connexion_inscription modal with CSS
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/base.css">
    <link rel="stylesheet" href="/assets/connexion_inscription.css">
    <title>Test Form</title>
</head>
<body>
    <?php echo $twig->render('connexion_inscription.twig.html'); ?>
    <script src="/assets/connexion_inscription.js"></script>
    <script src="/assets/role-selector.js"></script>
</body>
</html>
