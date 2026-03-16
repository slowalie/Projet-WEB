<?php
require_once __DIR__ . '/../vendor/autoload.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// On récupère l'URL demandée
$url = $_SERVER['REQUEST_URI'];

// On appelle le routeur
require_once __DIR__ . '/../router/router.php';


?>