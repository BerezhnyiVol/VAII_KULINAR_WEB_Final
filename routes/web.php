<?php

require_once __DIR__ . '/../app/Controllers/RecipeController.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';

$basePath = '/VAII_KULINAR_WEB/public/index.php';
$uri = str_replace($basePath, '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

$controller = new RecipeController();
$authController = new AuthController();





switch ($uri) {

    case '/':
    case '/login':
        $authController->showLoginForm();
        break;
    case '/register':
        $authController->showRegisterForm();
        break;
    case '/register/attempt':
        $authController->register();
        break;
    case '/create-admin':
        $authController->createAdmin();
        break;

    case '/login/attempt': // Обработка формы входа
        $authController->login();
        break;
    case '/logout':
        $authController->logout();
        break;
    case '/recipes':
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }
        $controller->index();
        break;
    case (preg_match('/^\/recipes\/search$/', $uri) ? true : false):
        $controller->search();
        break;


    case (preg_match('/^\/recipe\/(\d+)$/', $uri, $matches) ? true : false):
        $controller->show($matches[1]);
        break;

    case '/recipe/create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->store();
        } else {
            $controller->create();
            }

        break;

    case '/home': // Маршрут для главной страницы
        require_once __DIR__ . '/../app/Views/pages/home.view.php';

        break;

    case (preg_match('/^\/recipe\/delete\/(\d+)$/', $uri, $matches) ? true : false):
        $controller->delete($matches[1]);
        break;
    case (preg_match('/^\/recipe\/edit\/(\d+)$/', $uri, $matches) ? true : false):
        $controller->edit($matches[1]);
        break;

    case (preg_match('/^\/recipe\/update\/(\d+)$/', $uri, $matches) ? true : false):
        $controller->update($matches[1]);
        break;



    default:
        http_response_code(404);
        echo "Stránka neexistuje.";
        break;
}
