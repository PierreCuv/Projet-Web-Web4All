<?php
declare(strict_types=1);
namespace Controllers;
use Core\Controller;
use Core\Auth;
use Core\CSRF;
use Core\Session;
use Models\WishlistModel;

class WishlistController extends Controller
{
    private WishlistModel $wishlistModel;

    public function __construct()
    {
        parent::__construct();
        $this->wishlistModel = new WishlistModel();
    }

    public function index(): void
    {
        $this->view->render('wishlist/index', [
            'title'   => 'Ma wish-list – ' . APP_NAME,
            'offers'  => $this->wishlistModel->findByEtudiant(Auth::getEtudiantId()),
            'success' => Session::getFlash('success'),
        ]);
    }

    public function add(array $params): void
    {
        CSRF::verify();
        $this->wishlistModel->add(Auth::getEtudiantId(), (int) $params['id']);
        $this->redirectWithFlash("/offres/{$params['id']}", 'success', 'Ajouté à votre wish-list.');
    }

    public function remove(array $params): void
    {
        CSRF::verify();
        $this->wishlistModel->remove(Auth::getEtudiantId(), (int) $params['id']);
        $this->redirectWithFlash('/ma-wishlist', 'success', 'Retiré de votre wish-list.');
    }
}
