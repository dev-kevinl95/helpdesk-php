<?php

/**
 * Front Controller
 * Todas las peticiones pasan por aquí
 */

session_start();

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/routes/web.php';
require_once BASE_PATH . '/routes/api.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/') ?: '/';

// Buscar en rutas de API
if (isset($apiRoutes[$uri]) || isset($apiRoutes[$uri . '/'])) {
    $handler = $apiRoutes[$uri] ?? $apiRoutes[$uri . '/'];
    header('Content-Type: application/json');
    call_user_func($handler);
    exit;
}

// Buscar en rutas web (exactas)
if (isset($webRoutes[$uri])) {
    $handler = $webRoutes[$uri];
    call_user_func($handler);
    exit;
}

// Rutas dinámicas: /tickets/{id} y /tickets/{id}/comment
if (preg_match('#^/tickets/(\d+)$#', $uri, $matches)) {
    require_once BASE_PATH . '/app/Controllers/TicketController.php';
    TicketController::show($matches[1]);
    exit;
}

if (preg_match('#^/tickets/(\d+)/comment$#', $uri, $matches)) {
    require_once BASE_PATH . '/app/Controllers/TicketController.php';
    TicketController::addComment($matches[1]);
    exit;
}

if (preg_match('#^/tickets/(\d+)/assign$#', $uri, $matches)) {
    require_once BASE_PATH . '/app/Controllers/TicketController.php';
    TicketController::assign($matches[1]);
    exit;
}

if (preg_match('#^/tickets/(\d+)/edit$#', $uri, $matches)) {
    require_once BASE_PATH . '/app/Controllers/TicketController.php';
    TicketController::edit($matches[1]);
    exit;
}

if (preg_match('#^/tickets/(\d+)/status$#', $uri, $matches)) {
    require_once BASE_PATH . '/app/Controllers/TicketController.php';
    TicketController::updateStatus($matches[1]);
    exit;
}

if (preg_match('#^/tickets/(\d+)/delete$#', $uri, $matches)) {
    require_once BASE_PATH . '/app/Controllers/TicketController.php';
    TicketController::delete($matches[1]);
    exit;
}

// Admin: /admin/users/{id}/edit y /admin/users/{id}/delete
if (preg_match('#^/admin/users/(\d+)/edit$#', $uri, $matches)) {
    require_once BASE_PATH . '/app/Controllers/AdminController.php';
    AdminController::editUser($matches[1]);
    exit;
}

if (preg_match('#^/admin/users/(\d+)/delete$#', $uri, $matches)) {
    require_once BASE_PATH . '/app/Controllers/AdminController.php';
    AdminController::deleteUser($matches[1]);
    exit;
}

// API dinámicas: /api/tickets/{id}, /api/tickets/{id}/comments, /api/users/{id}/tickets, /api/users/{id}
if (preg_match('#^/api/#', $uri)) {
    require_once BASE_PATH . '/app/Controllers/Api/TicketApiController.php';
    require_once BASE_PATH . '/app/Controllers/Api/UserApiController.php';
    header('Content-Type: application/json');

    if (preg_match('#^/api/users#', $uri)) {
        UserApiController::handle();
    } else {
        TicketApiController::handle();
    }
    exit;
}

// Ruta no encontrada
http_response_code(404);
echo '<h1>404 - Página no encontrada</h1>';
