<?php

declare(strict_types=1);

namespace Core;

/**
 * Routeur URL.
 * Parse les URLs et dispatch vers le bon contrôleur.
 */
class Router
{
    private array $routes = [];

    public function get(string $path, string $controller, string $action, array $roles = []): void
    {
        $this->addRoute('GET', $path, $controller, $action, $roles);
    }

    public function post(string $path, string $controller, string $action, array $roles = []): void
    {
        $this->addRoute('POST', $path, $controller, $action, $roles);
    }

    private function addRoute(string $method, string $path, string $controller, string $action, array $roles): void
    {
        $pattern = preg_replace('/\/:([^\/]+)/', '/(?P<$1>[^/]+)', $path);
        $pattern = '#^' . $pattern . '$#';
        $this->routes[] = compact('method', 'path', 'pattern', 'controller', 'action', 'roles');
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $base = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
        if ($base !== '/' && str_starts_with($uri, $base)) {
            $uri = substr($uri, strlen($base));
        }
        $uri = '/' . trim($uri, '/') ?: '/';

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) continue;

            if (preg_match($route['pattern'], $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                Middleware::checkAccess($route['roles']);

                $controllerClass = 'Controllers\\' . $route['controller'];
                $controller = new $controllerClass();
                $controller->{$route['action']}($params);
                return;
            }
        }

        $this->notFound();
    }

    private function notFound(): void
    {
        http_response_code(404);
        $view = new View();
        $view->render('errors/404', ['title' => 'Page introuvable']);
    }
}
