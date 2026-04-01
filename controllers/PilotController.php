<?php
declare(strict_types=1);
namespace Controllers;
use Core\Controller;
use Core\CSRF;
use Core\Pagination;
use Core\Session;
use Models\UserModel;

class PilotController extends Controller
{
    private UserModel $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new UserModel();
    }

    public function index(): void
    {
        $page  = max(1, (int) $this->query('page', 1));
        $total = $this->userModel->countPilots();
        $pager = new Pagination($total, $page);
        $this->view->render('pilots/index', [
            'title'   => 'Pilotes – ' . APP_NAME,
            'pilots'  => $this->userModel->findAllPilots(ITEMS_PER_PAGE, ($page-1)*ITEMS_PER_PAGE),
            'pager'   => $pager,
            'success' => Session::getFlash('success'),
        ]);
    }

    public function show(array $params): void
    {
        $pilot = $this->userModel->findFullById((int) $params['id']);
        if (!$pilot || $pilot['role'] !== 'pilote') $this->redirect('/pilotes');
        $this->view->render('pilots/show', [
            'title' => $pilot['prenom'] . ' ' . $pilot['nom'] . ' – ' . APP_NAME,
            'pilot' => $pilot,
        ]);
    }

    public function create(): void
    {
        $this->view->render('pilots/form', [
            'title'  => 'Créer un pilote – ' . APP_NAME,
            'pilot'  => null,
            'action' => APP_URL . '/pilotes/creer',
            'error'  => Session::getFlash('error'),
        ]);
    }

    public function store(): void
    {
        CSRF::verify();
        if (!$this->validateRequired(['nom', 'prenom', 'email', 'password'])) {
            Session::flash('error', 'Tous les champs sont obligatoires.');
            $this->redirect('/pilotes/creer');
        }
        if ($this->userModel->findByEmail($this->input('email'))) {
            Session::flash('error', 'Cet email est déjà utilisé.');
            $this->redirect('/pilotes/creer');
        }
        $this->userModel->createWithPassword([
            'nom'      => $this->input('nom'),
            'prenom'   => $this->input('prenom'),
            'email'    => $this->input('email'),
            'password' => $this->input('password'),
            'role'     => 'pilote',
        ]);
        $this->redirectWithFlash('/pilotes', 'success', 'Pilote créé.');
    }

    public function edit(array $params): void
    {
        $pilot = $this->userModel->findFullById((int) $params['id']);
        if (!$pilot) $this->redirect('/pilotes');
        $this->view->render('pilots/form', [
            'title'  => 'Modifier – ' . APP_NAME,
            'pilot'  => $pilot,
            'action' => APP_URL . '/pilotes/' . $params['id'] . '/modifier',
            'error'  => Session::getFlash('error'),
        ]);
    }

    public function update(array $params): void
    {
        CSRF::verify();
        $this->userModel->updateUser((int) $params['id'], [
            'nom'    => $this->input('nom'),
            'prenom' => $this->input('prenom'),
            'email'  => $this->input('email'),
        ]);
        if ($this->input('password')) {
            $this->userModel->updatePassword((int) $params['id'], $this->input('password'));
        }
        $this->redirectWithFlash("/pilotes/{$params['id']}", 'success', 'Pilote mis à jour.');
    }

    public function destroy(array $params): void
    {
        CSRF::verify();
        $this->userModel->deleteUser((int) $params['id']);
        $this->redirectWithFlash('/pilotes', 'success', 'Pilote supprimé.');
    }
}
