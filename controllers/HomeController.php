<?php
declare(strict_types=1);
namespace Controllers;
use Core\Controller;
use Core\Session;
use Models\OfferModel;
use Models\CompanyModel;

class HomeController extends Controller
{
    public function index(): void
    {
        $offerModel   = new OfferModel();
        $companyModel = new CompanyModel();
        $this->view->render('home', [
            'title'           => APP_NAME . ' – Trouvez votre stage',
            'latest_offers'   => $offerModel->search([], 6, 0),
            'total_offers'    => $offerModel->countSearch(),
            'total_companies' => $companyModel->countSearch(),
            'success'         => Session::getFlash('success'),
        ]);
    }
}
