<?php

declare(strict_types=1);

require_once __DIR__ . '/controller.php';
require_once __DIR__ . '/../models/offresModel.php';

use App\Models\OffresModel;
use App\Models\Database;


class detailOffresController extends Controller
{
    private const MAX_UPLOAD_SIZE = 5242880; // 5 MB

    private OffresModel $offresModel;

    public function __construct($twig)
    {
        parent::__construct($twig);
        $database = new Database('localhost', 'root', 'A2#DevWeb!', 'ideastage_BDD');
        $this->offresModel = new OffresModel($database);
    }

    public function index(int $id_offres): string
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $this->handleApply($id_offres);
        }

        $offre = $this->offresModel->getOffreById($id_offres);
        
        return $this->render('detail-offre.twig.html', [
            'page' => 'offres',
            'offre' => $offre,
            'apply_status' => $_GET['apply'] ?? null,
            
        ]);
    }

    private function handleApply(int $offerId): void
    {
        if (!$this->isAuthenticated()) {
            header('Location: /detail-offre/' . $offerId . '?apply=login_required');
            exit;
        }

        $role = (string) ($_SESSION['user_role'] ?? '');
        $userId = (int) ($_SESSION['user_id'] ?? 0);

        if ($role !== 'etudiant' || $userId <= 0) {
            header('Location: /detail-offre/' . $offerId . '?apply=forbidden');
            exit;
        }

        try {
            $cvPath = $this->processUpload(
                'cv_file',
                ['pdf', 'doc', 'docx'],
                [
                    'application/pdf',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                ],
                $userId,
                true
            );

            $lettrePath = $this->processUpload(
                'lettre_file',
                ['pdf', 'doc', 'docx'],
                [
                    'application/pdf',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                ],
                $userId,
                false
            );

            $applied = $this->offresModel->submitApplication($offerId, $userId, $cvPath, $lettrePath);
            $status = $applied ? 'success' : 'error';
        } catch (\RuntimeException $exception) {
            $status = 'invalid_file';
        } catch (\Throwable $exception) {
            $status = 'error';
        }

        header('Location: /detail-offre/' . $offerId . '?apply=' . $status . '#apply-form');
        exit;
    }

    private function processUpload(
        string $fieldName,
        array $allowedExtensions,
        array $allowedMimeTypes,
        int $userId,
        bool $required
    ): ?string {
        if (!isset($_FILES[$fieldName]) || !is_array($_FILES[$fieldName])) {
            if ($required) {
                throw new \RuntimeException('Missing required file');
            }

            return null;
        }

        $upload = $_FILES[$fieldName];
        $error = (int) ($upload['error'] ?? UPLOAD_ERR_NO_FILE);

        if ($error === UPLOAD_ERR_NO_FILE) {
            if ($required) {
                throw new \RuntimeException('Missing required file');
            }

            return null;
        }

        if ($error !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('Upload failed');
        }

        $tmpName = (string) ($upload['tmp_name'] ?? '');
        $originalName = (string) ($upload['name'] ?? '');
        $size = (int) ($upload['size'] ?? 0);

        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            throw new \RuntimeException('Invalid upload');
        }

        if ($size <= 0 || $size > self::MAX_UPLOAD_SIZE) {
            throw new \RuntimeException('Invalid file size');
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

        $relativeDir = '/uploads/candidatures/' . $userId;
        $targetDir = __DIR__ . '/../../public' . $relativeDir;

        if (!is_dir($targetDir) && !mkdir($targetDir, 0775, true) && !is_dir($targetDir)) {
            throw new \RuntimeException('Cannot create directory');
        }

        $safeField = preg_replace('/[^a-z0-9_]/i', '_', $fieldName) ?: 'file';
        $filename = sprintf('%s_%s_%s.%s', $safeField, date('YmdHis'), bin2hex(random_bytes(4)), $extension);
        $targetPath = $targetDir . '/' . $filename;

        if (!move_uploaded_file($tmpName, $targetPath)) {
            throw new \RuntimeException('Cannot move file');
        }

        return $relativeDir . '/' . $filename;
    }
}