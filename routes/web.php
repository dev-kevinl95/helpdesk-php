<?php

/**
 * Rutas Web
 * Mapea URLs a controladores
 */

$webRoutes = [
    '/' => function() {
        if (isset($_SESSION['user_id'])) {
            header('Location: /dashboard');
        } else {
            header('Location: /login');
        }
        exit;
    },
    '/login' => function() {
        require_once BASE_PATH . '/app/Controllers/AuthController.php';
        AuthController::login();
    },
    '/logout' => function() {
        require_once BASE_PATH . '/app/Controllers/AuthController.php';
        AuthController::logout();
    },
    '/dashboard' => function() {
        require_once BASE_PATH . '/app/Controllers/DashboardController.php';
        DashboardController::index();
    },
    '/tickets' => function() {
        require_once BASE_PATH . '/app/Controllers/TicketController.php';
        TicketController::index();
    },
    '/tickets/create' => function() {
        require_once BASE_PATH . '/app/Controllers/TicketController.php';
        TicketController::create();
    },
];
