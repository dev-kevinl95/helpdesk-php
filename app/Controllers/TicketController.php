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

        if ($user['role'] === 'Admin') {
            // Admin ve todos
            $tickets = $ticketModel->findAll();
        } elseif ($user['role'] === 'Cliente') {
            // Cliente solo ve los que creó
            $tickets = $ticketModel->findByCreator($user['id']);
        } else {
            // Técnico ve los asignados y los que creó
            $tickets = $ticketModel->findByUser($user['id']);
        }

        require_once BASE_PATH . '/app/Views/tickets/index.php';
    }

    /**
     * Mostrar formulario para crear ticket
     */
    public static function create() {
        AuthController::requireAuth();

        $user = AuthController::user();
        $error = '';
        $technicians = ($user['role'] !== 'Cliente') ? (new User())->findTechnicians() : [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title       = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $priority    = $_POST['priority'] ?? '';
            $assignedTo  = $_POST['assigned_to'] ?? null;

            // Validar
            if (empty($title) || empty($description) || empty($priority)) {
                $error = 'Todos los campos son obligatorios';
            } elseif (!in_array($priority, ['Baja', 'Media', 'Alta'])) {
                $error = 'Prioridad no válida';
            } else {
                $ticketModel = new Ticket();
                $ticketId = $ticketModel->create($title, $description, $priority, $user['id']);

                // Asignar técnico si se seleccionó
                if ($assignedTo && $user['role'] !== 'Cliente') {
                    $ticketModel->update($ticketId, ['assigned_to' => $assignedTo]);
                }

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

        $user = AuthController::user();
        $ticketModel = new Ticket();
        $commentModel = new Comment();
        $userModel = new User();

        $ticket   = $ticketModel->findById($id);
        $comments = $commentModel->findByTicket($id);
        $technicians = ($user['role'] !== 'Cliente') ? $userModel->findTechnicians() : [];

        if (!$ticket) {
            http_response_code(404);
            echo '<h1>Ticket no encontrado</h1>';
            return;
        }

        // Cliente solo puede ver sus propios tickets
        if ($user['role'] === 'Cliente' && $ticket['created_by'] != $user['id']) {
            header("Location: /tickets");
            exit;
        }

        require_once BASE_PATH . '/app/Views/tickets/show.php';
    }

    /**
     * Asignar ticket a un técnico
     * Solo Admin o técnico asignado
     */
    public static function assign($ticketId) {
        AuthController::requireAuth();

        $user = AuthController::user();
        $ticketModel = new Ticket();
        $ticket = $ticketModel->findById($ticketId);

        if (!$ticket) {
            header("Location: /tickets");
            exit;
        }

        // Solo Admin o técnico asignado puede reasignar
        if ($user['role'] === 'Cliente') {
            header("Location: /tickets/$ticketId");
            exit;
        }

        if ($user['role'] === 'Tecnico' && $ticket['assigned_to'] != $user['id']) {
            header("Location: /tickets/$ticketId");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $assignedTo = $_POST['assigned_to'] ?? null;
            $ticketModel->update($ticketId, ['assigned_to' => $assignedTo ?: null]);
        }

        header("Location: /tickets/$ticketId?assigned=1");
        exit;
    }

    /**
     * Agregar comentario a un ticket
     */
    public static function addComment($ticketId) {
        AuthController::requireAuth();

        $user = AuthController::user();
        $ticketModel = new Ticket();
        $ticket = $ticketModel->findById($ticketId);

        if (!$ticket) {
            header("Location: /tickets");
            exit;
        }

        // Cliente solo puede comentar en sus propios tickets
        if ($user['role'] === 'Cliente' && $ticket['created_by'] != $user['id']) {
            header("Location: /tickets");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $content = trim($_POST['content'] ?? '');

            if (!empty($content)) {
                $commentModel = new Comment();
                $commentModel->create($ticketId, $user['id'], $content);
            }
        }

        header("Location: /tickets/$ticketId");
        exit;
    }

    /**
     * Formulario para editar ticket
     * Solo Admin o técnico asignado
     */
    public static function edit($id) {
        AuthController::requireAuth();

        $user = AuthController::user();
        $ticketModel = new Ticket();
        $ticket = $ticketModel->findById($id);

        if (!$ticket) {
            http_response_code(404);
            echo '<h1>Ticket no encontrado</h1>';
            return;
        }

        // Solo Admin o técnico asignado puede editar
        if ($user['role'] === 'Cliente' || ($user['role'] === 'Tecnico' && $ticket['assigned_to'] != $user['id'])) {
            header("Location: /tickets/$id");
            exit;
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title       = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $priority    = $_POST['priority'] ?? '';

            if (empty($title) || empty($description) || empty($priority)) {
                $error = 'Todos los campos son obligatorios';
            } elseif (!in_array($priority, ['Baja', 'Media', 'Alta'])) {
                $error = 'Prioridad no válida';
            } else {
                $ticketModel->update($id, [
                    'title'       => $title,
                    'description' => $description,
                    'priority'    => $priority,
                ]);
                header("Location: /tickets/$id?updated=1");
                exit;
            }
        }

        require_once BASE_PATH . '/app/Views/tickets/edit.php';
    }

    /**
     * Cambiar estado del ticket
     * Solo Admin o técnico asignado
     */
    public static function updateStatus($id) {
        AuthController::requireAuth();

        $user = AuthController::user();
        $ticketModel = new Ticket();
        $ticket = $ticketModel->findById($id);

        if (!$ticket) {
            header("Location: /tickets");
            exit;
        }

        // Solo Admin o técnico asignado
        if ($user['role'] === 'Cliente' || ($user['role'] === 'Tecnico' && $ticket['assigned_to'] != $user['id'])) {
            header("Location: /tickets/$id");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = $_POST['status'] ?? '';

            if (in_array($status, ['Pendiente', 'En progreso', 'Resuelto'])) {
                $ticketModel->update($id, ['status' => $status]);
            }
        }

        header("Location: /tickets/$id?status_changed=1");
        exit;
    }

    /**
     * Eliminar ticket
     * Solo Admin
     */
    public static function delete($id) {
        AuthController::requireAuth();

        $user = AuthController::user();

        // Solo Admin puede eliminar
        if ($user['role'] !== 'Admin') {
            header("Location: /tickets/$id");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ticketModel = new Ticket();
            $ticketModel->delete($id);
            header("Location: /tickets?deleted=1");
            exit;
        }

        header("Location: /tickets/$id");
        exit;
    }
}
