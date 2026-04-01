<?php
declare(strict_types=1);
namespace Controllers;
use Core\Controller;
use Core\Auth;
use Core\CSRF;
use Core\Pagination;
use Core\Session;
use Models\UserModel;

class StudentController extends Controller
{
    private UserModel $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new UserModel();
    }

    public function index(): void
    {
        $search = $this->query('search');
        $page   = max(1, (int) $this->query('page', 1));
        if (Auth::isPilot()) {
            $piloteId = Auth::getPiloteId();
            $total    = $this->userModel->countStudents('', $piloteId);
            $students = $this->userModel->findStudentsByPilot($piloteId, ITEMS_PER_PAGE, ($page-1)*ITEMS_PER_PAGE);
        } else {
            $total    = $this->userModel->countStudents($search);
            $students = $this->userModel->searchStudents($search, ITEMS_PER_PAGE, ($page-1)*ITEMS_PER_PAGE);
        }
        $pager = new Pagination($total, $page);
        $this->view->render('students/index', [
            'title'    => 'Étudiants – ' . APP_NAME,
            'students' => $students,
            'search'   => $search,
            'pager'    => $pager,
            'success'  => Session::getFlash('success'),
        ]);
    }

    public function show(array $params): void
    {
        $student = $this->userModel->findFullById((int) $params['id']);
        if (!$student || $student['role'] !== 'etudiant') $this->redirect('/etudiants');
        $this->view->render('students/show', [
            'title'   => $student['prenom'] . ' ' . $student['nom'] . ' – ' . APP_NAME,
            'student' => $student,
        ]);
    }

    public function create(): void
    {
        $this->view->render('students/form', [
            'title'   => 'Créer un étudiant – ' . APP_NAME,
            'student' => null,
            'pilots'  => $this->userModel->findAllPilots(),
            'action'  => APP_URL . '/etudiants/creer',
            'error'   => Session::getFlash('error'),
        ]);
    }

    public function store(): void
    {
        CSRF::verify();
        if (!$this->validateRequired(['nom', 'prenom', 'email', 'password'])) {
            Session::flash('error', 'Tous les champs sont obligatoires.');
            $this->redirect('/etudiants/creer');
        }
        if ($this->userModel->findByEmail($this->input('email'))) {
            Session::flash('error', 'Cet email est déjà utilisé.');
            $this->redirect('/etudiants/creer');
        }
        $piloteId = Auth::isPilot() ? Auth::getPiloteId() : ($this->input('id_pilote') ?: null);
        $this->userModel->createWithPassword([
            'nom'       => $this->input('nom'),
            'prenom'    => $this->input('prenom'),
            'email'     => $this->input('email'),
            'password'  => $this->input('password'),
            'role'      => 'etudiant',
            'promotion' => $this->input('promotion'),
            'id_pilote' => $piloteId,
        ]);
        $this->redirectWithFlash('/etudiants', 'success', 'Étudiant créé.');
    }

    public function edit(array $params): void
    {
        $student = $this->userModel->findFullById((int) $params['id']);
        if (!$student) $this->redirect('/etudiants');
        $this->view->render('students/form', [
            'title'   => 'Modifier – ' . APP_NAME,
            'student' => $student,
            'pilots'  => $this->userModel->findAllPilots(),
            'action'  => APP_URL . '/etudiants/' . $params['id'] . '/modifier',
            'error'   => Session::getFlash('error'),
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
        $this->userModel->updateEtudiant((int) $params['id'], [
            'promotion' => $this->input('promotion'),
            'id_pilote' => $this->input('id_pilote') ?: null,
        ]);
        if ($this->input('password')) {
            $this->userModel->updatePassword((int) $params['id'], $this->input('password'));
        }
        $this->redirectWithFlash("/etudiants/{$params['id']}", 'success', 'Étudiant mis à jour.');
    }

    public function destroy(array $params): void
    {
        CSRF::verify();
        $this->userModel->deleteUser((int) $params['id']);
        $this->redirectWithFlash('/etudiants', 'success', 'Étudiant supprimé.');
    }
}
