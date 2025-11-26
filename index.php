<?php
session_start();

require_once 'controllers/HomeController.php';
require_once 'controllers/AuthController.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'home';

switch ($action) {
    case 'login':
        $auth = new AuthController();
        $auth->login();
        break;
    case 'register':
        $auth = new AuthController();
        $auth->register();
        break;
    case 'logout':
        $auth = new AuthController();
        $auth->logout();
        break;
    default:
        $controller = new HomeController();
        $controller->index();
        break;
}
?>