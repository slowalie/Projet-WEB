<?php

declare(strict_types=1);

require_once __DIR__ . '/controller.php';
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/candidatModel.php';

use App\Models\CandidatModel;
use App\Models\Database;

class EspaceCandidatController extends Controller
{
    private const MAX_UPLOAD_SIZE = 5242880; // 5 MB

    public function index()
    {
        $this->requireRole(['etudiant', 'pilote', 'admin']);

        $userId = (int) ($_SESSION['user_id'] ?? 0);
        if ($userId <= 0) {
            $this->redirectUnauthorized('login_required');
        }

        $database = new Database('localhost', 'root', 'A2#DevWeb!', 'ideastage_BDD');
        $candidatModel = new CandidatModel($database);

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $this->handleProfileUpdate($candidatModel, $userId);
        }

        $candidat = $candidatModel->getByUserId($userId);

        return $this->render('espace-candidat.twig.html', [
            'page' => 'espace-candidat',
            'candidat' => $candidat,
            'profile_update_status' => $_GET['update'] ?? null,
        ]);
    }

    private function handleProfileUpdate(CandidatModel $candidatModel, int $userId): void
    {
        $role = (string) ($_SESSION['user_role'] ?? '');
        if ($role !== 'etudiant') {
            header('Location: /espace-candidat?update=forbidden');
            exit;
        }

        $existing = $candidatModel->getByUserId($userId) ?? [];

        $data = [
            'titre_profil' => trim((string) ($_POST['titre_profil'] ?? '')),
            'disponibilite' => trim((string) ($_POST['disponibilite'] ?? '')),
            'cv' => isset($existing['cv']) ? (string) $existing['cv'] : null,
            'add_doc' => isset($existing['add_doc']) ? (string) $existing['add_doc'] : null,
            'photo' => isset($existing['photo']) ? (string) $existing['photo'] : null,
        ];

        foreach (['titre_profil', 'disponibilite'] as $textField) {
            $value = $data[$textField];
            $data[$textField] = $value === '' ? null : $value;
        }

        try {
            $data['cv'] = $this->processUpload(
                'cv_file',
                ['pdf', 'doc', 'docx'],
                [
                    'application/pdf',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                ],
                $userId,
                $data['cv']
            );

            $data['add_doc'] = $this->processUpload(
                'add_doc_file',
                ['pdf', 'doc', 'docx'],
                [
                    'application/pdf',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                ],
                $userId,
                $data['add_doc']
            );

            $data['photo'] = $this->processUpload(
                'photo_file',
                ['jpg', 'jpeg', 'png', 'webp'],
                ['image/jpeg', 'image/png', 'image/webp'],
                $userId,
                $data['photo']
            );

            $updated = $candidatModel->updateProfile($userId, $data);
            $status = $updated ? 'success' : 'error';
        } catch (\RuntimeException $exception) {
            $status = 'invalid_file';
        } catch (\Throwable $exception) {
            $status = 'error';
        }

        header('Location: /espace-candidat?update=' . $status . '#parametres');
        exit;
    }

    private function processUpload(
        string $fieldName,
        array $allowedExtensions,
        array $allowedMimeTypes,
        int $userId,
        ?string $currentPath
    ): ?string {
        if (!isset($_FILES[$fieldName]) || !is_array($_FILES[$fieldName])) {
            return $currentPath;
        }

        $upload = $_FILES[$fieldName];
        $error = (int) ($upload['error'] ?? UPLOAD_ERR_NO_FILE);

        if ($error === UPLOAD_ERR_NO_FILE) {
            return $currentPath;
        }

        if ($error !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('Upload failed');
        }

        $tmpName = (string) ($upload['tmp_name'] ?? '');
        $originalName = (string) ($upload['name'] ?? '');
        $size = (int) ($upload['size'] ?? 0);

        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            throw new \RuntimeException('Invalid uploaded file');
        }

        if ($size <= 0 || $size > self::MAX_UPLOAD_SIZE) {
            throw new \RuntimeException('File size not allowed');
        }

        $extension = strtolower((string) pathinfo($originalName, PATHINFO_EXTENSION));
        if ($extension === '' || !in_array($extension, $allowedExtensions, true)) {
            throw new \RuntimeException('Invalid extension');
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = $finfo ? (string) finfo_file($finfo, $tmpName) : '';
        if ($finfo) {
            finfo_close($finfo);
        }

        if ($mimeType === '' || !in_array($mimeType, $allowedMimeTypes, true)) {
            throw new \RuntimeException('Invalid mime type');
        }

        $relativeDir = '/uploads/candidats/' . $userId;
        $targetDir = __DIR__ . '/../../public' . $relativeDir;
        if (!is_dir($targetDir) && !mkdir($targetDir, 0775, true) && !is_dir($targetDir)) {
            throw new \RuntimeException('Cannot create upload directory');
        }

        $safeField = preg_replace('/[^a-z0-9_]/i', '_', $fieldName) ?: 'file';
        $filename = sprintf(
            '%s_%s_%s.%s',
            $safeField,
            date('YmdHis'),
            bin2hex(random_bytes(4)),
            $extension
        );

        $targetPath = $targetDir . '/' . $filename;
        if (!move_uploaded_file($tmpName, $targetPath)) {
            throw new \RuntimeException('Cannot save uploaded file');
        }

        return $relativeDir . '/' . $filename;
    }
}
