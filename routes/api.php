<?php

/**
 * Rutas API REST
 * Todas las respuestas son JSON
 */

$apiRoutes = [
    '/api/tickets' => function() {
        require_once BASE_PATH . '/app/Controllers/Api/TicketApiController.php';
        TicketApiController::handle();
    },
    '/api/tickets/comments' => function() {
        require_once BASE_PATH . '/app/Controllers/Api/TicketApiController.php';
        TicketApiController::handle();
    },
    '/api/users' => function() {
        require_once BASE_PATH . '/app/Controllers/Api/UserApiController.php';
        UserApiController::handle();
    },
];
