<?php

require_once BASE_PATH . '/app/Models/Ticket.php';
require_once BASE_PATH . '/app/Models/Comment.php';
require_once BASE_PATH . '/app/Models/User.php';
require_once BASE_PATH . '/app/Controllers/AuthController.php';

/**
 * Controlador: Ticket
 * CRUD de tickets
 */
class TicketController {

    /**
     * Listar todos los tickets
     */
    public static function index() {
        AuthController::requireAuth();

        $user = AuthController::user();
        $ticketModel = new Ticket();

        // Admin ve todos, otros ven los suyos
        if ($user['role'] === 'Admin') {
            $tickets = $ticketModel->findAll();
        } else {
            $tickets = $ticketModel->findByUser($user['id']);
        }

        require_once BASE_PATH . '/app/Views/tickets/index.php';
    }

    /**
     * Mostrar formulario para crear ticket
     */
    public static function create() {
        AuthController::requireAuth();

        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title       = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $priority    = $_POST['priority'] ?? '';

            // Validar
            if (empty($title) || empty($description) || empty($priority)) {
                $error = 'Todos los campos son obligatorios';
            } elseif (!in_array($priority, ['Baja', 'Media', 'Alta'])) {
                $error = 'Prioridad no válida';
            } else {
                $user = AuthController::user();
                $ticketModel = new Ticket();
                $ticketModel->create($title, $description, $priority, $user['id']);

                header('Location: /tickets?created=1');
                exit;
            }
        }

        require_once BASE_PATH . '/app/Views/tickets/create.php';
    }

    /**
     * Ver detalle de un ticket
     */
    public static function show($id) {
        AuthController::requireAuth();

        $ticketModel = new Ticket();
        $commentModel = new Comment();

        $ticket   = $ticketModel->findById($id);
        $comments = $commentModel->findByTicket($id);

        if (!$ticket) {
            http_response_code(404);
            echo '<h1>Ticket no encontrado</h1>';
            return;
        }

        require_once BASE_PATH . '/app/Views/tickets/show.php';
    }

    /**
     * Agregar comentario a un ticket
     */
    public static function addComment($ticketId) {
        AuthController::requireAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $content = trim($_POST['content'] ?? '');

            if (!empty($content)) {
                $user = AuthController::user();
                $commentModel = new Comment();
                $commentModel->create($ticketId, $user['id'], $content);
            }
        }

        header("Location: /tickets/$ticketId");
        exit;
    }
}
