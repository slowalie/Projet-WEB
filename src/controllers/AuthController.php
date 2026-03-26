<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/UserModel.php';

use App\Models\Database;

class AuthController
{
    private UserModel $userModel;

    public function __construct()
    {
        $database = new Database('localhost', 'root', 'A2#DevWeb!', 'ideastage_BDD');
        $this->userModel = new UserModel($database);
    }

    public function register(array $postData): void
    {
        $email = trim((string)($postData['emailSup'] ?? ''));
        $password = (string)($postData['mdpSup'] ?? '');
        $confirmPassword = (string)($postData['confirmMdpSup'] ?? '');
        $nom = trim((string)($postData['nomSup'] ?? ''));
        $prenom = trim((string)($postData['prenomSup'] ?? ''));

        // Validation
        if (empty($email) || empty($password) || empty($confirmPassword)) {
            $this->redirectWithStatus('signup', 'missing_fields');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->redirectWithStatus('signup', 'invalid_email');
        }

        if ($password !== $confirmPassword) {
            $this->redirectWithStatus('signup', 'password_mismatch');
        }

        if (strlen($password) < 8) {
            $this->redirectWithStatus('signup', 'weak_password');
        }

        if ($nom === '') {
            $nom = 'Utilisateur';
        }

        if ($prenom === '') {
            $prenom = 'Nouveau';
        }

        // Check if email exists
        if ($this->userModel->existsByEmail($email)) {
            $this->redirectWithStatus('signup', 'email_exists');
        }

        // Register user
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $this->userModel->createUser($nom, $prenom, $email, $passwordHash, 'etudiant');

        // Store session
        $_SESSION['user_email'] = $email;
        $_SESSION['is_authenticated'] = true;

        $this->redirectWithStatus('login', 'register_success');
    }

    public function login(array $postData): void
    {
        $email = trim((string)($postData['emailLin'] ?? ''));
        $password = (string)($postData['mdpLin'] ?? '');

        // Validation
        if (empty($email) || empty($password)) {
            $this->redirectWithStatus('login', 'missing_fields');
        }

        // Check user and verify password
        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, (string)$user['mdp_user'])) {
            $this->redirectWithStatus('login', 'invalid_credentials');
        }

        // Store session
        $_SESSION['user_id'] = (int)$user['id_user'];
        $_SESSION['user_email'] = (string)$user['mail_user'];
        $_SESSION['is_authenticated'] = true;

        $this->redirectWithStatus('login', 'login_success');
    }

    private function redirectWithStatus(string $mode, string $status): void
    {
        $target = $this->getRedirectTarget();
        $separator = str_contains($target, '?') ? '&' : '?';
        header('Location: ' . $target . $separator . 'auth_mode=' . urlencode($mode) . '&auth_status=' . urlencode($status));
        exit;
    }

    private function getRedirectTarget(): string
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/home';
        $path = parse_url($referer, PHP_URL_PATH) ?: '/home';

        $allowed = ['/', '/home', '/offres', '/espace-candidat', '/espace-pilote', '/tasks'];
        return in_array($path, $allowed, true) ? $path : '/home';
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
        header('Location: /home');
        exit;
    }
}
