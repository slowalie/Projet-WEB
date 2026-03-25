<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/UserModel.php';

use App\Models\Database;
use App\Models\UserModel;

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
        $nom = trim((string)($postData['nomSup'] ?? ''));
        $prenom = trim((string)($postData['prenomSup'] ?? ''));
        $role = trim((string)($postData['role'] ?? 'etudiant'));

        // Validate role
        if (!in_array($role, ['admin', 'pilot', 'etudiant'])) {
            $role = 'etudiant';
        }

        // Validation
        if (empty($email) || empty($password) || empty($nom) || empty($prenom)) {
            $this->redirectWithStatus('signup', 'missing_fields');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->redirectWithStatus('signup', 'invalid_email');
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
        
        if (!$this->userModel->createUser($nom, $prenom, $email, $passwordHash, $role)) {
            $this->redirectWithStatus('signup', 'db_error');
        }

        // Get user ID and store session
        $user = $this->userModel->findByEmail($email);
        if (!$user) {
            $this->redirectWithStatus('signup', 'db_error');
        }
        
        $_SESSION['user_id'] = (int)$user['id_user'];
        $_SESSION['user_email'] = $email;
        $_SESSION['user_role'] = $role;
        $_SESSION['is_authenticated'] = true;

        // Redirect based on role
        $redirectPath = match($role) {
            'admin' => '/home',
            'pilot' => '/espace-pilote',
            'etudiant' => '/espace-candidat',
            default => '/home'
        };

        header('Location: ' . $redirectPath);
        exit;
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

        // Get user's role from database
        $role = (string)$user['role_user'];
        
        // Ensure role is valid
        $validRoles = ['admin', 'pilot', 'etudiant'];
        if (!in_array($role, $validRoles, true)) {
            $role = 'etudiant'; // Default to student if invalid
        }

        // Store session
        $_SESSION['user_id'] = (int)$user['id_user'];
        $_SESSION['user_email'] = (string)$user['mail_user'];
        $_SESSION['user_role'] = $role;
        $_SESSION['is_authenticated'] = true;

        // Redirect based on role
        $redirectPath = match($role) {
            'admin' => '/home',
            'pilot' => '/espace-pilote',
            'etudiant' => '/espace-candidat',
            default => '/home'
        };

        header('Location: ' . $redirectPath);
        exit;
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

    public function showRegisterStudentForm($twig)
    {
        $context = [
            'is_authenticated' => isset($_SESSION['user_id']) && !empty($_SESSION['user_id']),
            'user_role' => $_SESSION['user_role'] ?? null,
            'user_email' => $_SESSION['user_email'] ?? null,
        ];
        return $twig->render('inscrire-etudiant.twig.html', $context);
    }

    public function registerStudent(array $postData): void
    {
        $nom = trim((string)($postData['nom'] ?? ''));
        $prenom = trim((string)($postData['prenom'] ?? ''));
        $email = trim((string)($postData['email'] ?? ''));
        $password = (string)($postData['mdp'] ?? '');

        // Validation
        if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
            header('Location: /auth/register-student?status=missing_fields');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Location: /auth/register-student?status=invalid_email');
            exit;
        }

        if (strlen($password) < 8) {
            header('Location: /auth/register-student?status=weak_password');
            exit;
        }

        // Check if email already exists
        if ($this->userModel->existsByEmail($email)) {
            header('Location: /auth/register-student?status=email_exists');
            exit;
        }

        // Register student
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        if (!$this->userModel->createUser($nom, $prenom, $email, $passwordHash, 'etudiant')) {
            header('Location: /auth/register-student?status=db_error');
            exit;
        }

        // Redirect with success message
        header('Location: /auth/register-student?status=register_success');
        exit;
    }

    public function logout(): void
    {
        // Unset all session variables
        $_SESSION = [];
        
        // Delete the session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        
        // Properly close and destroy the session
        session_write_close();
        session_destroy();
        
        header('Location: /home');
        exit;
    }
}
