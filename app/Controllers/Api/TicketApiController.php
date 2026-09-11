<?php

require_once BASE_PATH . '/app/Models/Ticket.php';
require_once BASE_PATH . '/app/Models/Comment.php';
require_once BASE_PATH . '/app/Models/User.php';

/**
 * API Controller: Tickets
 * Endpoints JSON para CRUD de tickets
 */
class TicketApiController {

    private static $ticketModel;
    private static $commentModel;
    private static $userModel;

    private static function init() {
        self::$ticketModel  = new Ticket();
        self::$commentModel = new Comment();
        self::$userModel    = new User();
    }

    /**
     * Enrutar según el método y URI
     */
    public static function handle() {
        header('Content-Type: application/json');
        self::init();

        $method = $_SERVER['REQUEST_METHOD'];
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri    = rtrim($uri, '/');

        // /api/tickets/{id}
        if (preg_match('#^/api/tickets/(\d+)$#', $uri, $m)) {
            self::routeSingle($method, $m[1]);
            return;
        }

        // /api/tickets/{id}/status
        if (preg_match('#^/api/tickets/(\d+)/status$#', $uri, $m)) {
            self::routeStatus($method, $m[1]);
            return;
        }

        // /api/tickets/{id}/comments
        if (preg_match('#^/api/tickets/(\d+)/comments$#', $uri, $m)) {
            self::routeComments($method, $m[1]);
            return;
        }

        // /api/users/{id}/tickets
        if (preg_match('#^/api/users/(\d+)/tickets$#', $uri, $m)) {
            self::routeUserTickets($method, $m[1]);
            return;
        }

        // /api/tickets
        if ($uri === '/api/tickets') {
            self::routeCollection($method);
            return;
        }

        http_response_code(404);
        echo json_encode(['error' => 'Ruta no encontrada']);
    }

    // =============================================
    // GET/POST /api/tickets
    // =============================================
    private static function routeCollection($method) {
        switch ($method) {
            case 'GET':
                self::listTickets();
                break;
            case 'POST':
                self::createTicket();
                break;
            default:
                self::methodNotAllowed();
        }
    }

    // =============================================
    // GET/PUT/DELETE /api/tickets/{id}
    // =============================================
    private static function routeSingle($method, $id) {
        switch ($method) {
            case 'GET':
                self::getTicket($id);
                break;
            case 'PUT':
                self::updateTicket($id);
                break;
            case 'DELETE':
                self::deleteTicket($id);
                break;
            default:
                self::methodNotAllowed();
        }
    }

    // =============================================
    // GET/POST /api/tickets/{id}/comments
    // =============================================
    private static function routeComments($method, $ticketId) {
        switch ($method) {
            case 'GET':
                self::listComments($ticketId);
                break;
            case 'POST':
                self::createComment($ticketId);
                break;
            default:
                self::methodNotAllowed();
        }
    }

    // =============================================
    // GET /api/users/{id}/tickets
    // =============================================
    private static function routeUserTickets($method, $userId) {
        if ($method !== 'GET') {
            self::methodNotAllowed();
            return;
        }

        $tickets = self::$ticketModel->findByUser($userId);
        echo json_encode($tickets);
    }

    // =============================================
    // PATCH /api/tickets/{id}/status
    // =============================================
    private static function routeStatus($method, $id) {
        if ($method !== 'PATCH') {
            self::methodNotAllowed();
            return;
        }

        self::changeStatus($id);
    }

    // =============================================
    // Acciones
    // =============================================

    /**
     * GET /api/tickets
     * Opcionales: ?status=Pendiente, ?priority=Alta
     */
    private static function listTickets() {
        $status   = $_GET['status']   ?? null;
        $priority = $_GET['priority'] ?? null;

        if ($status) {
            $tickets = self::$ticketModel->findByStatus($status);
        } elseif ($priority) {
            $tickets = self::$ticketModel->findByPriority($priority);
        } else {
            $tickets = self::$ticketModel->findAll();
        }

        echo json_encode($tickets);
    }

    /**
     * GET /api/tickets/{id}
     */
    private static function getTicket($id) {
        $ticket = self::$ticketModel->findById($id);

        if (!$ticket) {
            http_response_code(404);
            echo json_encode(['error' => 'Ticket no encontrado']);
            return;
        }

        echo json_encode($ticket);
    }

    /**
     * POST /api/tickets
     * Body JSON: { title, description, priority, created_by }
     */
    private static function createTicket() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            http_response_code(400);
            echo json_encode(['error' => 'JSON inválido']);
            return;
        }

        $title       = $data['title']       ?? '';
        $description = $data['description'] ?? '';
        $priority    = $data['priority']    ?? '';
        $createdBy   = $data['created_by']  ?? null;

        // Validar campos obligatorios
        if (empty($title) || empty($description) || empty($priority) || !$createdBy) {
            http_response_code(400);
            echo json_encode(['error' => 'Faltan campos: title, description, priority, created_by']);
            return;
        }

        // Validar prioridad
        if (!in_array($priority, ['Baja', 'Media', 'Alta'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Prioridad inválida. Usa: Baja, Media, Alta']);
            return;
        }

        $id = self::$ticketModel->create($title, $description, $priority, $createdBy);

        http_response_code(201);
        echo json_encode([
            'message' => 'Ticket creado',
            'id'      => $id,
        ]);
    }

    /**
     * PUT /api/tickets/{id}
     * Body JSON: { title, description, priority, status, assigned_to }
     */
    private static function updateTicket($id) {
        $ticket = self::$ticketModel->findById($id);

        if (!$ticket) {
            http_response_code(404);
            echo json_encode(['error' => 'Ticket no encontrado']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            http_response_code(400);
            echo json_encode(['error' => 'JSON inválido']);
            return;
        }

        // Validar prioridad si se envía
        if (isset($data['priority']) && !in_array($data['priority'], ['Baja', 'Media', 'Alta'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Prioridad inválida']);
            return;
        }

        // Validar estado si se envía
        if (isset($data['status']) && !in_array($data['status'], ['Pendiente', 'En progreso', 'Resuelto'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Estado inválido']);
            return;
        }

        self::$ticketModel->update($id, $data);

        echo json_encode([
            'message' => 'Ticket actualizado',
            'id'      => (int)$id,
        ]);
    }

    /**
     * DELETE /api/tickets/{id}
     */
    private static function deleteTicket($id) {
        $ticket = self::$ticketModel->findById($id);

        if (!$ticket) {
            http_response_code(404);
            echo json_encode(['error' => 'Ticket no encontrado']);
            return;
        }

        self::$ticketModel->delete($id);

        echo json_encode(['message' => 'Ticket eliminado']);
    }

    /**
     * PATCH /api/tickets/{id}/status
     * Body JSON: { status: "En progreso" }
     */
    private static function changeStatus($id) {
        $ticket = self::$ticketModel->findById($id);

        if (!$ticket) {
            http_response_code(404);
            echo json_encode(['error' => 'Ticket no encontrado']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $status = $data['status'] ?? '';

        if (!in_array($status, ['Pendiente', 'En progreso', 'Resuelto'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Estado inválido. Usa: Pendiente, En progreso, Resuelto']);
            return;
        }

        self::$ticketModel->update($id, ['status' => $status]);

        echo json_encode([
            'message' => 'Estado actualizado',
            'id'      => (int)$id,
            'status'  => $status,
        ]);
    }

    /**
     * GET /api/tickets/{id}/comments
     */
    private static function listComments($ticketId) {
        $ticket = self::$ticketModel->findById($ticketId);

        if (!$ticket) {
            http_response_code(404);
            echo json_encode(['error' => 'Ticket no encontrado']);
            return;
        }

        $comments = self::$commentModel->findByTicket($ticketId);
        echo json_encode($comments);
    }

    /**
     * POST /api/tickets/{id}/comments
     * Body JSON: { user_id, content }
     */
    private static function createComment($ticketId) {
        $ticket = self::$ticketModel->findById($ticketId);

        if (!$ticket) {
            http_response_code(404);
            echo json_encode(['error' => 'Ticket no encontrado']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            http_response_code(400);
            echo json_encode(['error' => 'JSON inválido']);
            return;
        }

        $userId  = $data['user_id'] ?? null;
        $content = $data['content'] ?? '';

        if (!$userId || empty($content)) {
            http_response_code(400);
            echo json_encode(['error' => 'Faltan campos: user_id, content']);
            return;
        }

        $id = self::$commentModel->create($ticketId, $userId, $content);

        http_response_code(201);
        echo json_encode([
            'message' => 'Comentario agregado',
            'id'      => $id,
        ]);
    }

    private static function methodNotAllowed() {
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
    }
}
