<?php
// 1. Wajib ditaruh paling atas untuk menangani login/logout
session_start(); 
ob_start(); // Mencegah error "Cannot modify header info"

// 2. Load Controllers
require_once 'controllers/HomeController.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/AdminController.php';
if (file_exists('controllers/CartController.php')) {
    require_once 'controllers/CartController.php';
}


// 3. Routing
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

    case 'cart':
        if (class_exists('CartController')) {
            $cart = new CartController();
            $cart->index();
        }
        break;
        
    case 'checkout':
        if (class_exists('CartController')) {
            $cart = new CartController();
            $cart->checkout();
        }
        break;

    case 'add_to_cart':
        if (class_exists('CartController')) {
            $cart = new CartController();
            $cart->addToCart();
        }
        break;

    case 'update_cart': // Routing Update
        if (class_exists('CartController')) {
            $cart = new CartController();
            $cart->update_cart();
        }
        break;

    case 'remove_item': // Routing Hapus
        if (class_exists('CartController')) {
            $cart = new CartController();
            $cart->remove_item();
        }
        break;

    case 'dashboard':
        $admin = new AdminController();
        $admin->dashboard();
        break;

    default:
        $controller = new HomeController();
        $controller->index();
        break;
}

ob_end_flush(); // Kirim output buffer
?>


