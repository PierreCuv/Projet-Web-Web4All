<?php
declare(strict_types=1);
namespace Controllers;
use Core\Controller;

class StaticController extends Controller
{
    public function mentions(): void
    {
        $this->view->render('static/mentions', [
            'title' => 'Mentions légales – ' . APP_NAME,
        ]);
    }
}
