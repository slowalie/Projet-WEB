<?php

declare(strict_types=1);

require_once __DIR__ . '/controller.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/Database.php';

use App\Models\Database;

class EspaceAdminController extends Controller
{
    private UserModel $userModel;

    public function __construct(object $twig)
    {
        parent::__construct($twig);
        $database = new Database('localhost', 'root', 'A2#DevWeb!', 'ideastage_BDD');
        $this->userModel = new UserModel($database);
    }

    public function index()
    {
        $this->requireRole('admin');

        $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        
        // Handle form submissions
        if ($requestMethod === 'POST') {
            $formType = $_POST['form_type'] ?? '';
            
            if ($formType === 'create_pilote') {
                $this->handleCreatePilote();
            } elseif ($formType === 'edit_pilote') {
                $this->handleEditPilote();
            } elseif ($formType === 'delete_pilote') {
                $this->handleDeletePilote();
            }
        }

        // Get search query if present
        $searchQuery = trim((string)($_GET['search'] ?? ''));
        
        if ($searchQuery !== '') {
            $pilotes = $this->userModel->searchUsersByRole('pilote', $searchQuery);
            $searchStatus = 'search_performed';
        } else {
            $pilotes = $this->userModel->getuserbyrole('pilote');
            $searchStatus = null;
        }

        return $this->render('espace-admin.twig.html', [
            'page' => 'espace-admin',
            'pilotes' => $pilotes,
            'search_query' => $searchQuery,
            'search_status' => $searchStatus,
            'pilote_status' => $_GET['pilote'] ?? null,
        ]);
    }

    private function handleCreatePilote(): void
    {
        $nom = trim((string)($_POST['nom_pilote'] ?? ''));
        $prenom = trim((string)($_POST['prenom_pilote'] ?? ''));
        $email = trim((string)($_POST['email_pilote'] ?? ''));
        $password = (string)($_POST['password_pilote'] ?? '');

        // Validation
        if ($nom === '' || $prenom === '' || $email === '' || $password === '') {
            header('Location: /espace-admin?pilote=missing_fields');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Location: /espace-admin?pilote=invalid_email');
            exit;
        }

        if (strlen($password) < 8) {
            header('Location: /espace-admin?pilote=weak_password');
            exit;
        }

        // Check if email exists
        if ($this->userModel->existsByEmail($email)) {
            header('Location: /espace-admin?pilote=email_exists');
            exit;
        }

        // Create pilote
        try {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $this->userModel->createUserWithRoleProfile($nom, $prenom, $email, $passwordHash, 'pilote');
            header('Location: /espace-admin?pilote=create_success');
            exit;
        } catch (\Throwable $exception) {
            header('Location: /espace-admin?pilote=create_error');
            exit;
        }
    }

    private function handleEditPilote(): void
    {
        $idPilote = (int)($_POST['id_pilote'] ?? 0);
        $nom = trim((string)($_POST['nom_pilote'] ?? ''));
        $prenom = trim((string)($_POST['prenom_pilote'] ?? ''));
        $email = trim((string)($_POST['email_pilote'] ?? ''));
        $password = (string)($_POST['password_pilote'] ?? '');

        // Validation
        if ($idPilote === 0) {
            header('Location: /espace-admin?pilote=invalid_id');
            exit;
        }

        if ($nom === '' || $prenom === '' || $email === '') {
            header('Location: /espace-admin?pilote=missing_fields');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Location: /espace-admin?pilote=invalid_email');
            exit;
        }

        // Get current user to check if email changed
        $currentUser = $this->userModel->getUserById($idPilote);
        if ($currentUser === null) {
            header('Location: /espace-admin?pilote=user_not_found');
            exit;
        }

        // Check if new email exists (but not if it's the same user)
        if ($currentUser['mail_user'] !== $email && $this->userModel->existsByEmail($email)) {
            header('Location: /espace-admin?pilote=email_exists');
            exit;
        }

        // If password is provided, validate it
        if ($password !== '' && strlen($password) < 8) {
            header('Location: /espace-admin?pilote=weak_password');
            exit;
        }

        try {
            // Update user info
            $this->userModel->updateUser($idPilote, $nom, $prenom, $email);

            // Update password if provided
            if ($password !== '') {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $database = new Database('localhost', 'root', 'A2#DevWeb!', 'ideastage_BDD');
                $pdo = $database->getConnection();
                $stmt = $pdo->prepare('UPDATE Utilisateurs SET mdp_user = :mdp_user WHERE id_user = :id_user');
                $stmt->execute([
                    'mdp_user' => $passwordHash,
                    'id_user' => $idPilote,
                ]);
            }

            header('Location: /espace-admin?pilote=edit_success');
            exit;
        } catch (\Throwable $exception) {
            header('Location: /espace-admin?pilote=edit_error');
            exit;
        }
    }

    private function handleDeletePilote(): void
    {
        $idPilote = (int)($_POST['id_pilote'] ?? 0);

        if ($idPilote === 0) {
            header('Location: /espace-admin?pilote=invalid_id');
            exit;
        }

        // Verify the user exists and is a pilote
        $pilote = $this->userModel->getUserById($idPilote);
        if ($pilote === null || $pilote['role_user'] !== 'pilote') {
            header('Location: /espace-admin?pilote=user_not_found');
            exit;
        }

        try {
            $this->userModel->deleteUserById($idPilote);
            header('Location: /espace-admin?pilote=delete_success');
            exit;
        } catch (\Throwable $exception) {
            header('Location: /espace-admin?pilote=delete_error');
            exit;
        }
    }
}
