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
}
