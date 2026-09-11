<?php

require_once BASE_PATH . '/app/Models/Ticket.php';
require_once BASE_PATH . '/app/Controllers/AuthController.php';

/**
 * Controlador: Dashboard
 * Muestra resumen del sistema
 */
class DashboardController {

    public static function index() {
        AuthController::requireAuth();

        $user = AuthController::user();
        $ticketModel = new Ticket();

        // Contar tickets por estado
        $counts = $ticketModel->countByStatus();

        // Tickets recientes
        if ($user['role'] === 'Admin') {
            $recentTickets = $ticketModel->findAll();
        } else {
            $recentTickets = $ticketModel->findByUser($user['id']);
        }
        $recentTickets = array_slice($recentTickets, 0, 5);

        require_once BASE_PATH . '/app/Views/dashboard/index.php';
    }
}
