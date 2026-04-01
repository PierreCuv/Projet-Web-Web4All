<?php

declare(strict_types=1);

namespace Core;

/**
 * Moteur de template PHP.
 * Rend une vue dans le layout principal.
 */
class View
{
    private string $viewPath;
    private string $layoutPath;

    public function __construct()
    {
        $this->viewPath   = ROOT_PATH . '/views/';
        $this->layoutPath = ROOT_PATH . '/views/layout/base.php';
    }

    /**
     * Rend une vue dans le layout.
     *
     * @param string $view  Chemin relatif depuis /views/ (ex: 'companies/index')
     * @param array  $data  Variables injectées dans la vue ET le layout
     */
    public function render(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);

        ob_start();
        $viewFile = $this->viewPath . $view . '.php';
        if (!file_exists($viewFile)) {
            ob_end_clean();
            die("Vue introuvable : {$viewFile}");
        }
        require $viewFile;
        $content = ob_get_clean();

        require $this->layoutPath;
    }

    /**
     * Rend une vue sans layout.
     * Utile pour les réponses AJAX ou les emails.
     */
    public function renderPartial(string $view, array $data = []): string
    {
        extract($data, EXTR_SKIP);
        ob_start();
        require $this->viewPath . $view . '.php';
        return ob_get_clean();
    }

    /**
     * Échappe une valeur pour l'affichage HTML.
     * Protection anti-XSS — à utiliser sur TOUTES les données utilisateur.
     */
    public static function e(mixed $value): string
    {
        return htmlspecialchars((string)$value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}
