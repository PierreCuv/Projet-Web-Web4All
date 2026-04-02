<?php
declare(strict_types=1);
namespace Controllers;
use Core\Controller;
use Core\Auth;
use Core\CSRF;
use Core\Session;
use Models\UserModel;

class AuthController extends Controller
{
    private UserModel $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new UserModel();
    }

    public function showLogin(): void
    {
        if (Auth::isLoggedIn()) $this->redirect('/');
        $this->view->render('auth/login', [
            'title' => 'Connexion – ' . APP_NAME,
            'error' => Session::getFlash('error'),
        ]);
    }

    public function login(): void
    {
        CSRF::verify();
        $email    = $this->input('email');
        $password = $this->input('password');

        if (empty($email) || empty($password)) {
            Session::flash('error', 'Veuillez remplir tous les champs.');
            $this->redirect('/login');
        }

        $user = $this->userModel->findByEmail($email);
        if (!$user || !password_verify($password, $user['mot_de_passe'])) {
            Session::flash('error', 'Identifiants incorrects.');
            $this->redirect('/login');
        }

        Auth::login($user);
        $redirect = Session::get('redirect_after_login', '/');
        Session::remove('redirect_after_login');
        $this->redirect($redirect);
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirectWithFlash('/', 'success', 'Vous êtes déconnecté.');
    }


public function showRegister(): void
{
    if (Auth::isLoggedIn()) $this->redirect('/');
    $this->view->render('auth/register', [
        'title' => 'Inscription – ' . APP_NAME,
        'error' => Session::getFlash('error'),
    ]);
}

public function register(): void
{
    CSRF::verify();

    $prenom     = $this->input('prenom');
    $nom        = $this->input('nom');
    $email      = $this->input('email');
    $password   = $this->input('password');
    $confirm    = $this->input('password_confirm');
    $role       = $this->input('role');
    $inviteCode = $this->input('invite_code');

    $rolesAutorises = ['etudiant', 'pilote', 'entreprise'];

    if (empty($prenom) || empty($nom) || empty($email) || empty($password)) {
        Session::flash('error', 'Veuillez remplir tous les champs.');
        $this->redirect('/register');
    }

    if (!in_array($role, $rolesAutorises, true)) {
        Session::flash('error', 'Rôle invalide.');
        $this->redirect('/register');
    }

    if ($password !== $confirm) {
        Session::flash('error', 'Les mots de passe ne correspondent pas.');
        $this->redirect('/register');
    }

    if (strlen($password) < 8) {
        Session::flash('error', 'Le mot de passe doit contenir au moins 8 caractères.');
        $this->redirect('/register');
    }

    // Vérification du code selon le rôle
    if ($role === 'pilote') {
        if (empty($inviteCode) || $inviteCode !== PILOTE_INVITE_CODE) {
            Session::flash('error', 'Code d\'invitation pilote invalide.');
            $this->redirect('/register');
        }
    }

    if ($role === 'entreprise') {
        if (empty($inviteCode) || $inviteCode !== ENTREPRISE_INVITE_CODE) {
            Session::flash('error', 'Code d\'invitation entreprise invalide.');
            $this->redirect('/register');
        }
    }

    if ($this->userModel->findByEmail($email)) {
        Session::flash('error', 'Cet email est déjà utilisé.');
        $this->redirect('/register');
    }

    $this->userModel->createWithPassword([
        'prenom'   => $prenom,
        'nom'      => $nom,
        'email'    => $email,
        'password' => $password,
        'role'     => $role,
    ]);

    $this->redirectWithFlash('/login', 'success', 'Compte créé ! Vous pouvez vous connecter.');
}
}