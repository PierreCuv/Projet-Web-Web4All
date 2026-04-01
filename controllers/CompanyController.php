<?php
declare(strict_types=1);
namespace Controllers;
use Core\Controller;
use Core\Auth;
use Core\CSRF;
use Core\Pagination;
use Core\Session;
use Models\CompanyModel;

class CompanyController extends Controller
{
    private CompanyModel $companyModel;

    public function __construct()
    {
        parent::__construct();
        $this->companyModel = new CompanyModel();
    }

    public function index(): void
    {
        $search = $this->query('search');
        $page   = max(1, (int) $this->query('page', 1));
        $total  = $this->companyModel->countSearch($search);
        $pager  = new Pagination($total, $page);
        $this->view->render('companies/index', [
            'title'     => 'Entreprises – ' . APP_NAME,
            'companies' => $this->companyModel->search($search, $pager->limit, $pager->offset),
            'search'    => $search,
            'pager'     => $pager,
            'success'   => Session::getFlash('success'),
            'error'     => Session::getFlash('error'),
        ]);
    }

    public function show(array $params): void
    {
        $company = $this->companyModel->findWithStats((int) $params['id']);
        if (!$company) $this->redirect('/entreprises');
        $this->view->render('companies/show', [
            'title'       => $company['nom'] . ' – ' . APP_NAME,
            'company'     => $company,
            'evaluations' => $this->companyModel->findEvaluations((int) $params['id']),
        ]);
    }

    public function create(): void
    {
        $this->view->render('companies/form', [
            'title'   => 'Créer une entreprise – ' . APP_NAME,
            'company' => null,
            'action'  => APP_URL . '/entreprises/creer',
            'error'   => Session::getFlash('error'),
        ]);
    }

    public function store(): void
    {
        CSRF::verify();
        if (!$this->validateRequired(['nom', 'email'])) {
            Session::flash('error', 'Les champs obligatoires sont manquants.');
            $this->redirect('/entreprises/creer');
        }
        $id = $this->companyModel->create([
            'nom'       => $this->input('nom'),
            'secteur'   => $this->input('secteur'),
            'adresse'   => $this->input('adresse'),
            'email'     => $this->input('email'),
            'telephone' => $this->input('telephone'),
        ]);
        $this->redirectWithFlash("/entreprises/{$id}", 'success', 'Entreprise créée.');
    }

    public function edit(array $params): void
    {
        $company = $this->companyModel->findById((int) $params['id']);
        if (!$company) $this->redirect('/entreprises');
        $this->view->render('companies/form', [
            'title'   => 'Modifier – ' . APP_NAME,
            'company' => $company,
            'action'  => APP_URL . '/entreprises/' . $params['id'] . '/modifier',
            'error'   => Session::getFlash('error'),
        ]);
    }

    public function update(array $params): void
    {
        CSRF::verify();
        $this->companyModel->update((int) $params['id'], [
            'nom'       => $this->input('nom'),
            'secteur'   => $this->input('secteur'),
            'adresse'   => $this->input('adresse'),
            'email'     => $this->input('email'),
            'telephone' => $this->input('telephone'),
        ]);
        $this->redirectWithFlash("/entreprises/{$params['id']}", 'success', 'Entreprise mise à jour.');
    }

    public function evaluate(array $params): void
    {
        CSRF::verify();
        $note = (int) $this->input('note');
        if ($note < 1 || $note > 5) {
            Session::flash('error', 'Note invalide.');
            $this->redirect("/entreprises/{$params['id']}");
        }
        $etudiantId = Auth::getEtudiantId() ?? 0;
        $this->companyModel->addEvaluation((int) $params['id'], $etudiantId, $note, $this->input('commentaire'));
        $this->redirectWithFlash("/entreprises/{$params['id']}", 'success', 'Évaluation enregistrée.');
    }

    public function destroy(array $params): void
    {
        CSRF::verify();
        $this->companyModel->delete((int) $params['id']);
        $this->redirectWithFlash('/entreprises', 'success', 'Entreprise supprimée.');
    }
}
