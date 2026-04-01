<?php
declare(strict_types=1);
namespace Controllers;
use Core\Controller;
use Core\CSRF;
use Core\Pagination;
use Core\Session;
use Models\OfferModel;
use Models\CompanyModel;
use Models\CompetenceModel;

class OfferController extends Controller
{
    private OfferModel      $offerModel;
    private CompanyModel    $companyModel;
    private CompetenceModel $competenceModel;

    public function __construct()
    {
        parent::__construct();
        $this->offerModel      = new OfferModel();
        $this->companyModel    = new CompanyModel();
        $this->competenceModel = new CompetenceModel();
    }

    public function index(): void
    {
        $filters = [
            'search'       => $this->query('search'),
            'lieu'         => $this->query('lieu'),
            'id_competence'=> $this->query('id_competence'),
        ];
        $page  = max(1, (int) $this->query('page', 1));
        $total = $this->offerModel->countSearch($filters);
        $pager = new Pagination($total, $page);
        $this->view->render('offers/index', [
            'title'       => 'Offres – ' . APP_NAME,
            'offers'      => $this->offerModel->search($filters, $pager->limit, $pager->offset),
            'competences' => $this->competenceModel->findAllSorted(),
            'filters'     => $filters,
            'pager'       => $pager,
            'success'     => Session::getFlash('success'),
        ]);
    }

    public function show(array $params): void
    {
        $offer = $this->offerModel->findWithDetails((int) $params['id']);
        if (!$offer) $this->redirect('/offres');
        $this->view->render('offers/show', [
            'title' => $offer['titre'] . ' – ' . APP_NAME,
            'offer' => $offer,
        ]);
    }

    public function create(): void
    {
        $this->view->render('offers/form', [
            'title'       => 'Créer une offre – ' . APP_NAME,
            'offer'       => null,
            'companies'   => $this->companyModel->findAll(),
            'competences' => $this->competenceModel->findAllSorted(),
            'action'      => APP_URL . '/offres/creer',
        ]);
    }

    public function store(): void
    {
        CSRF::verify();
        if (!$this->validateRequired(['titre', 'id_entreprise', 'description'])) {
            Session::flash('error', 'Champs obligatoires manquants.');
            $this->redirect('/offres/creer');
        }
        $id = $this->offerModel->create([
            'titre'         => $this->input('titre'),
            'description'   => $this->input('description'),
            'lieu'          => $this->input('lieu'),
            'date_debut'    => $this->input('date_debut') ?: null,
            'date_fin'      => $this->input('date_fin')   ?: null,
            'remuneration'  => $this->input('remuneration') ?: null,
            'id_entreprise' => (int) $this->input('id_entreprise'),
        ]);
        $this->offerModel->syncCompetences($id, $_POST['competences'] ?? []);
        $this->redirectWithFlash("/offres/{$id}", 'success', 'Offre créée.');
    }

    public function edit(array $params): void
    {
        $offer = $this->offerModel->findWithDetails((int) $params['id']);
        if (!$offer) $this->redirect('/offres');
        $this->view->render('offers/form', [
            'title'            => 'Modifier – ' . APP_NAME,
            'offer'            => $offer,
            'companies'        => $this->companyModel->findAll(),
            'competences'      => $this->competenceModel->findAllSorted(),
            'selected_comp_ids'=> array_column($offer['competences'], 'id_competence'),
            'action'           => APP_URL . '/offres/' . $params['id'] . '/modifier',
        ]);
    }

    public function update(array $params): void
    {
        CSRF::verify();
        $this->offerModel->update((int) $params['id'], [
            'titre'         => $this->input('titre'),
            'description'   => $this->input('description'),
            'lieu'          => $this->input('lieu'),
            'date_debut'    => $this->input('date_debut') ?: null,
            'date_fin'      => $this->input('date_fin')   ?: null,
            'remuneration'  => $this->input('remuneration') ?: null,
            'id_entreprise' => (int) $this->input('id_entreprise'),
        ]);
        $this->offerModel->syncCompetences((int) $params['id'], $_POST['competences'] ?? []);
        $this->redirectWithFlash("/offres/{$params['id']}", 'success', 'Offre mise à jour.');
    }

    public function destroy(array $params): void
    {
        CSRF::verify();
        $this->offerModel->delete((int) $params['id']);
        $this->redirectWithFlash('/offres', 'success', 'Offre supprimée.');
    }

    public function statistics(): void
    {
        $this->view->render('offers/statistics', [
            'title' => 'Statistiques – ' . APP_NAME,
            'stats' => $this->offerModel->getStatistics(),
        ]);
    }
}
