<?php
namespace App\Core;

use App\Controller\ZooController;
class Router
{
    private $routes = [
        '/' => [
            'controller' => 'Zoo',
            'action' => 'addAnimal'
        ],
        '/profile' => [
        'controller' => 'Profile',
        'action' => 'index'
        ],
        '/profile/tickets' => [
            'controller' => 'Profile', 
            'action' => 'tickets'
        ],
        '/profile/sections' => [
            'controller' => 'Profile',
            'action' => 'sections'
        ],
        '/profile/roles' => [
            'controller' => 'Profile',
            'action' => 'roles'
        ],
        '/view' => [
            'controller' => 'Zoo', 
            'action' => 'viewAnimals'
        ],
        '/sections' => [
            'controller' => 'Zoo',
            'action' => 'showSections'
        ],
        '/tickets' => [
            'controller' => 'Zoo',
            'action' => 'buyTickets'
        ],
        '/login' => [
            'controller' => 'Auth',
            'action' => 'login'
        ],
        '/register' => [
            'controller' => 'Auth',
            'action' => 'register'
        ],
        '/logout' => [
            'controller' => 'Auth',
            'action' => 'logout'
        ]
    ];

    public function dispatch()
    {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    
        // Пропускаем проверку для публичных маршрутов
        $publicRoutes = ['/login', '/register', '/assets'];
        if (!in_array($path, $publicRoutes)) {
            if (!isset($_SESSION['user'])) {
                $_SESSION['redirect_to'] = $path;
                header("Location: /login");
                exit;
            }
        }
        
        if (array_key_exists($path, $this->routes)) {
            $route = $this->routes[$path];
            
            $controllerName = "App\\Controller\\" . $route['controller'] . "Controller";
            $action = $route['action'];
            
            if (class_exists($controllerName) && method_exists($controllerName, $action)) {
                (new $controllerName())->$action();
                return;
            }
        }
        
        // Если маршрут не найден
        http_response_code(404);
        echo "Страница не найдена";
    }
}