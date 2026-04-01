<?php
declare(strict_types=1);
namespace Controllers;
use Core\Controller;
use Core\Auth;
use Core\CSRF;
use Core\Session;
use Models\ApplicationModel;

class ApplicationController extends Controller
{
    private ApplicationModel $applicationModel;

    public function __construct()
    {
        parent::__construct();
        $this->applicationModel = new ApplicationModel();
    }

    public function store(array $params): void
    {
        CSRF::verify();
        $offreId    = (int) $params['id'];
        $etudiantId = Auth::getEtudiantId();

        if (!$etudiantId) {
            Session::flash('error', 'Profil étudiant introuvable.');
            $this->redirect("/offres/{$offreId}");
        }
        if ($this->applicationModel->hasApplied($etudiantId, $offreId)) {
            Session::flash('error', 'Vous avez déjà postulé à cette offre.');
            $this->redirect("/offres/{$offreId}");
        }

        $cvPath = $this->handleUpload('cv', $offreId, $etudiantId, 'cv');
        if (!$cvPath) {
            Session::flash('error', 'Le CV est obligatoire (PDF, max 5 Mo).');
            $this->redirect("/offres/{$offreId}");
        }
        $lmPath = $this->handleUpload('lettre_motivation', $offreId, $etudiantId, 'lm');

        $this->applicationModel->create([
            'id_offre'          => $offreId,
            'id_etudiant'       => $etudiantId,
            'cv'                => $cvPath,
            'lettre_motivation' => $lmPath,
            'statut'            => 'en_attente',
            'date_candidature'  => date('Y-m-d H:i:s'),
        ]);
        $this->redirectWithFlash('/mes-candidatures', 'success', 'Candidature envoyée !');
    }

    public function myApplications(): void
    {
        $etudiantId = Auth::getEtudiantId();
        $this->view->render('candidatures/my_list', [
            'title'        => 'Mes candidatures – ' . APP_NAME,
            'applications' => $etudiantId
                ? $this->applicationModel->findByEtudiant($etudiantId) : [],
            'success'      => Session::getFlash('success'),
        ]);
    }

    public function pilotView(): void
    {
        $piloteId = Auth::getPiloteId();
        $this->view->render('candidatures/pilot_list', [
            'title'        => 'Candidatures – ' . APP_NAME,
            'applications' => $piloteId
                ? $this->applicationModel->findByPilote($piloteId) : [],
        ]);
    }

    private function handleUpload(string $inputName, int $offreId,
                                   int $etudiantId, string $type): ?string
    {
        if (empty($_FILES[$inputName]['tmp_name'])) return null;
        $file = $_FILES[$inputName];
        if ($file['size'] > UPLOAD_MAX_SIZE) return null;
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        if (!in_array($finfo->file($file['tmp_name']), UPLOAD_ALLOWED_TYPES, true)) return null;
        $filename = sprintf('%s_%d_%d_%s.pdf', $type, $offreId, $etudiantId, bin2hex(random_bytes(4)));
        $destPath = UPLOAD_PATH . $filename;
        if (!move_uploaded_file($file['tmp_name'], $destPath)) return null;
        return 'uploads/' . $filename;
    }
}
