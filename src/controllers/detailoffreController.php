<?php

declare(strict_types=1);

require_once __DIR__ . '/controller.php';
require_once __DIR__ . '/../models/offresModel.php';

use App\Models\OffresModel;
use App\Models\Database;


class detailOffresController extends Controller
{


    private const MAX_UPLOAD_SIZE = 5242880; // 5 MB

    private const ALLOWED_EXTENSIONS = ['pdf', 'doc', 'docx'];

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

    private function handleApply(int $offreId): void
    {
        if (!isset($_SESSION['is_authenticated']) || $_SESSION['is_authenticated'] !== true || !isset($_SESSION['user_id'])) {
            $this->redirectWithApplyStatus($offreId, 'login_required');
        }

        $userRole = (string) ($_SESSION['user_role'] ?? '');
        if ($userRole !== 'etudiant') {
            $this->redirectWithApplyStatus($offreId, 'forbidden');
        }

        if (!isset($_FILES['cv_file'], $_FILES['lettre_file'])) {
            $this->redirectWithApplyStatus($offreId, 'missing_file');
        }

        $cvFile = $_FILES['cv_file'];
        $lettreFile = $_FILES['lettre_file'];

        $cvValidationStatus = $this->validateUpload($cvFile);
        if ($cvValidationStatus !== null) {
            $this->redirectWithApplyStatus($offreId, $cvValidationStatus);
        }

        $lettreValidationStatus = $this->validateUpload($lettreFile);
        if ($lettreValidationStatus !== null) {
            $this->redirectWithApplyStatus($offreId, $lettreValidationStatus);
        }

        $uploadDir = dirname(__DIR__, 2) . '/public/docs/candidatures/';
        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true) && !is_dir($uploadDir)) {
            error_log('Upload error: unable to create directory ' . $uploadDir);
            $this->redirectWithApplyStatus($offreId, 'upload_error');
        }

        if (!is_writable($uploadDir)) {
            error_log('Upload error: directory is not writable ' . $uploadDir . ' perms=' . substr(sprintf('%o', fileperms($uploadDir)), -4));
            $this->redirectWithApplyStatus($offreId, 'upload_error');
        }

        $cvFilename = $this->buildUploadName('cv', (string) $cvFile['name']);
        $lettreFilename = $this->buildUploadName('lettre', (string) $lettreFile['name']);
        $cvDestination = $uploadDir . $cvFilename;
        $lettreDestination = $uploadDir . $lettreFilename;
        $cvDbPath = '/docs/candidatures/' . $cvFilename;
        $lettreDbPath = '/docs/candidatures/' . $lettreFilename;

        $cvMoved = move_uploaded_file((string) $cvFile['tmp_name'], $cvDestination);
        $lettreMoved = move_uploaded_file((string) $lettreFile['tmp_name'], $lettreDestination);

        if (!$cvMoved || !$lettreMoved) {
            if ($cvMoved && is_file($cvDestination)) {
                @unlink($cvDestination);
            }
            if ($lettreMoved && is_file($lettreDestination)) {
                @unlink($lettreDestination);
            }

            error_log('Upload error: move_uploaded_file failed for offer ' . $offreId
                . ' cv_tmp=' . (string) $cvFile['tmp_name']
                . ' lettre_tmp=' . (string) $lettreFile['tmp_name']
                . ' cv_dest=' . $cvDestination
                . ' lettre_dest=' . $lettreDestination);
            $this->redirectWithApplyStatus($offreId, 'upload_error');
        }

        try {
            $saved = $this->offresModel->submitApplication(
                $offreId,
                (int) $_SESSION['user_id'],
                    $cvDbPath,
                    $lettreDbPath
            );

            if (!$saved) {
                $this->redirectWithApplyStatus($offreId, 'db_not_saved');
            }
        } catch (\Throwable $exception) {
            $this->redirectWithApplyStatus($offreId, 'db_not_saved');
        }

        $this->redirectWithApplyStatus($offreId, 'success');
    }

    private function validateUpload(array $file): ?string
    {
        if (!isset($file['error'], $file['size'], $file['name'], $file['tmp_name'])) {
            return 'invalid_file';
        }

        if ((int) $file['error'] !== UPLOAD_ERR_OK) {
            $errorCode = (int) $file['error'];
            if ($errorCode === UPLOAD_ERR_NO_FILE) {
                return 'missing_file';
            }

            if ($errorCode === UPLOAD_ERR_INI_SIZE || $errorCode === UPLOAD_ERR_FORM_SIZE) {
                return 'file_too_large';
            }

            return 'upload_error';
        }

        $size = (int) $file['size'];
        if ($size <= 0) {
            return 'invalid_file';
        }

        if ($size > self::MAX_UPLOAD_SIZE) {
            return 'file_too_large';
        }

        $extension = strtolower(pathinfo((string) $file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, self::ALLOWED_EXTENSIONS, true)) {
            return 'invalid_extension';
        }

        if (!is_uploaded_file((string) $file['tmp_name'])) {
            return 'upload_error';
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file((string) $file['tmp_name']);

        $allowedMimesByExtension = [
            'pdf' => ['application/pdf'],
            'doc' => ['application/msword', 'application/octet-stream'],
            'docx' => [
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/zip',
                'application/octet-stream',
            ],
        ];

        if (!is_string($mimeType) || !in_array($mimeType, $allowedMimesByExtension[$extension], true)) {
            return 'invalid_mime';
        }

        return null;
    }

    private function buildUploadName(string $prefix, string $originalName): string
    {
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        return sprintf('%s_%s.%s', $prefix, bin2hex(random_bytes(16)), $extension);
    }

    private function redirectWithApplyStatus(int $offreId, string $status): void
    {
        header('Location: /detail-offre/' . $offreId . '?apply=' . urlencode($status));
        exit();
    }

}