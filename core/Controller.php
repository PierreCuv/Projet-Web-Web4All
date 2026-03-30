<?php

declare(strict_types=1);

namespace Core;

/**
 * Contrôleur de base.
 * Tous les contrôleurs héritent de cette classe.
 */
abstract class Controller
{
    protected View $view;

    public function __construct()
    {
        $this->view = new View();
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . APP_URL . $path);
        exit;
    }

    protected function back(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? APP_URL . '/';
        header('Location: ' . $referer);
        exit;
    }

    protected function redirectWithFlash(string $path, string $type, string $message): void
    {
        Session::flash($type, $message);
        $this->redirect($path);
    }

    protected function input(string $key, mixed $default = ''): mixed
    {
        return isset($_POST[$key]) ? trim($_POST[$key]) : $default;
    }

    protected function query(string $key, mixed $default = ''): mixed
    {
        return isset($_GET[$key]) ? trim($_GET[$key]) : $default;
    }

    protected function validateRequired(array $fields): bool
    {
        foreach ($fields as $field) {
            if (empty($_POST[$field])) return false;
        }
        return true;
    }

    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
