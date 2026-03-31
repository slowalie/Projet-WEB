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
        if (!$this->isAuthenticated()) {
            $this->redirectWithStatus('login', 'unauthorized_registration');
        }

        $creatorRole = (string) ($_SESSION['user_role'] ?? '');
        if (!in_array($creatorRole, ['pilote', 'admin'], true)) {
            $this->redirectWithStatus('login', 'unauthorized_registration');
        }

        $email = trim((string)($postData['emailSup'] ?? ''));
        $password = (string)($postData['mdpSup'] ?? '');
        $role = (string)($postData['role'] ?? '');
        $nom = trim((string)($postData['nomSup'] ?? ''));
        $prenom = trim((string)($postData['prenomSup'] ?? ''));
        $normalizedRole = $this->normalizeUiRoleToDbRole($role);

        // Validation
        if (empty($email) || empty($password)) {
            $this->redirectWithStatus('signup', 'missing_fields');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->redirectWithStatus('signup', 'invalid_email');
        }

        if (strlen($password) < 8) {
            $this->redirectWithStatus('signup', 'weak_password');
        }

        if (empty($role)) {
            $this->redirectWithStatus('signup', 'missing_role');
        }

        if ($normalizedRole === 'admin') {
            $this->redirectWithStatus('signup', 'forbidden_role_creation');
        }

        if ($normalizedRole === 'pilote' && $creatorRole !== 'admin') {
            $this->redirectWithStatus('signup', 'forbidden_role_creation');
        }

        if ($normalizedRole !== 'etudiant' && $normalizedRole !== 'pilote') {
            $this->redirectWithStatus('signup', 'invalid_role');
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
        $this->userModel->createUserWithRoleProfile($nom, $prenom, $email, $passwordHash, $normalizedRole);

        $this->redirectWithStatus('login', 'register_success');
    }

    public function login(array $postData): void
    {
        $email = trim((string)($postData['emailLin'] ?? ''));
        $password = (string)($postData['mdpLin'] ?? '');
        $role = (string)($postData['role'] ?? '');
        $normalizedRole = $this->normalizeUiRoleToDbRole($role);

        // Validation
        if (empty($email) || empty($password) || empty($role)) {
            $this->redirectWithStatus('login', 'missing_fields');
        }

        // Check user and verify password
        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, (string)$user['mdp_user'])) {
            $this->redirectWithStatus('login', 'invalid_credentials');
        }

        if ((string) $user['role_user'] !== $normalizedRole) {
            $this->redirectWithStatus('login', 'invalid_role');
        }

        // Store session
        $_SESSION['user_id'] = (int)$user['id_user'];
        $_SESSION['user_email'] = (string)$user['mail_user'];
        $_SESSION['user_role'] = (string)$user['role_user'];
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

        $allowed = ['/', '/home', '/offres', '/espace-candidat', '/espace-pilote', '/tasks', '/entreprises'];
        return in_array($path, $allowed, true) ? $path : '/home';
    }

    private function isAuthenticated(): bool
    {
        return isset($_SESSION['is_authenticated']) && $_SESSION['is_authenticated'] === true;
    }

    private function normalizeUiRoleToDbRole(string $role): string
    {
        if ($role === 'candidat') {
            return 'etudiant';
        }

        return $role;
    }
}
