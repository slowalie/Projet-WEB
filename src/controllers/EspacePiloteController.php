<?php

declare(strict_types=1);

require_once __DIR__ . '/controller.php';

use App\Models\OffresModel;
use App\Models\EntreprisesModel;
use App\Models\LocalisationModel;
use App\Models\Database;    



class EspacePiloteController extends Controller
{
    public function index()
    {
        $this->requireRole(['pilote', 'admin']);

        $entreprisesModel = new EntreprisesModel(new Database('localhost', 'root', 'A2#DevWeb!', 'ideastage_BDD'));

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && ($_POST['form_type'] ?? '') === 'company_create') {
            $this->handleCompanyCreate($entreprisesModel);
        }

        $entreprises = $entreprisesModel->getEntreprises();
        return $this->render('espace-pilote.twig.html', [
            'page' => 'espace-pilote',
            'entreprises' => $entreprises,
            'company_status' => $_GET['company'] ?? null,
        ]);
    }

    private function handleCompanyCreate(EntreprisesModel $entreprisesModel): void
    {
        $nom = trim((string) ($_POST['nom_entreprise'] ?? ''));
        $logo = trim((string) ($_POST['logo_entreprise'] ?? ''));
        $description = trim((string) ($_POST['description_entreprise'] ?? ''));
        $noteRaw = trim((string) ($_POST['note_entreprise'] ?? ''));

        if ($nom === '') {
            header('Location: /espace-pilote?company=missing_name');
            exit;
        }

        if ($noteRaw !== '' && !is_numeric($noteRaw)) {
            header('Location: /espace-pilote?company=invalid_note');
            exit;
        }

        $note = $noteRaw === '' ? 0 : (float) $noteRaw;

        try {
            $entreprisesModel->addEntreprises($nom, $logo, $description, $note);
            header('Location: /espace-pilote?company=success');
            exit;
        } catch (\Throwable $exception) {
            header('Location: /espace-pilote?company=error');
            exit;
        }
    }
}
