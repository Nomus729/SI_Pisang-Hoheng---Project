<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_lifetime' => 86400,
        'cookie_secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax'
    ]);
}
ob_start(); // Mencegah error "Cannot modify header info"

// 2. Load Composer Autoloader & Environment Configuration
require_once __DIR__ . '/vendor/autoload.php';

if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\AdminController;
use App\Controllers\CartController;

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
        $cart = new CartController();
        $cart->index();
        break;

    case 'checkout':
        $cart = new CartController();
        $cart->checkout();
        break;

    case 'add_to_cart':
        $cart = new CartController();
        $cart->addToCart();
        break;

    case 'update_cart': // Routing Update
        $cart = new CartController();
        $cart->update_cart();
        break;

    case 'remove_item': // Routing Hapus
        $cart = new CartController();
        $cart->remove_item();
        break;

    case 'dashboard':
        $admin = new AdminController();
        $admin->dashboard();
        break;

    case 'place_order':
        $cart = new CartController();
        $cart->place_order();
        break;

    case 'my_orders':
        $cart = new CartController();
        $cart->my_orders();
        break;

    case 'reorder':
        $cart = new CartController();
        $cart->reorder();
        break;

    case 'info':
        $controller = new HomeController();
        $controller->info();
        break;

    default:
        $controller = new HomeController();
        $controller->index();
        break;
}

ob_end_flush(); // Kirim output buffer
?>
