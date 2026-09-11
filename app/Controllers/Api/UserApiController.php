<?php

require_once BASE_PATH . '/app/Models/User.php';

/**
 * API Controller: Users
 * Endpoints JSON para CRUD de usuarios
 */
class UserApiController {

    private static $userModel;

    private static function init() {
        self::$userModel = new User();
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

        // /api/users/{id}
        if (preg_match('#^/api/users/(\d+)$#', $uri, $m)) {
            self::routeSingle($method, $m[1]);
            return;
        }

        // /api/users
        if ($uri === '/api/users') {
            self::routeCollection($method);
            return;
        }

        http_response_code(404);
        echo json_encode(['error' => 'Ruta no encontrada']);
    }

    // =============================================
    // GET/POST /api/users
    // =============================================
    private static function routeCollection($method) {
        switch ($method) {
            case 'GET':
                self::listUsers();
                break;
            case 'POST':
                self::createUser();
                break;
            default:
                self::methodNotAllowed();
        }
    }

    // =============================================
    // GET/PUT/DELETE /api/users/{id}
    // =============================================
    private static function routeSingle($method, $id) {
        switch ($method) {
            case 'GET':
                self::getUser($id);
                break;
            case 'PUT':
                self::updateUser($id);
                break;
            case 'DELETE':
                self::deleteUser($id);
                break;
            default:
                self::methodNotAllowed();
        }
    }

    // =============================================
    // Acciones
    // =============================================

    /**
     * GET /api/users
     */
    private static function listUsers() {
        $users = self::$userModel->findAll();
        echo json_encode($users);
    }

    /**
     * GET /api/users/{id}
     */
    private static function getUser($id) {
        $user = self::$userModel->findById($id);

        if (!$user) {
            http_response_code(404);
            echo json_encode(['error' => 'Usuario no encontrado']);
            return;
        }

        echo json_encode($user);
    }

    /**
     * POST /api/users
     * Body JSON: { name, email, password, role }
     */
    private static function createUser() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            http_response_code(400);
            echo json_encode(['error' => 'JSON inválido']);
            return;
        }

        $name     = $data['name']     ?? '';
        $email    = $data['email']    ?? '';
        $password = $data['password'] ?? '';
        $role     = $data['role']     ?? '';

        if (empty($name) || empty($email) || empty($password) || empty($role)) {
            http_response_code(400);
            echo json_encode(['error' => 'Faltan campos: name, email, password, role']);
            return;
        }

        if (!in_array($role, ['Admin', 'Tecnico', 'Cliente'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Rol inválido']);
            return;
        }

        if (self::$userModel->emailExists($email)) {
            http_response_code(400);
            echo json_encode(['error' => 'El email ya está registrado']);
            return;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $id = self::$userModel->create($name, $email, $hashedPassword, $role);

        http_response_code(201);
        echo json_encode([
            'message' => 'Usuario creado',
            'id'      => $id,
        ]);
    }

    /**
     * PUT /api/users/{id}
     * Body JSON: { name, email, role, password (opcional) }
     */
    private static function updateUser($id) {
        $user = self::$userModel->findById($id);

        if (!$user) {
            http_response_code(404);
            echo json_encode(['error' => 'Usuario no encontrado']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            http_response_code(400);
            echo json_encode(['error' => 'JSON inválido']);
            return;
        }

        // Validar rol si se envía
        if (isset($data['role']) && !in_array($data['role'], ['Admin', 'Tecnico', 'Cliente'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Rol inválido']);
            return;
        }

        self::$userModel->update($id, $data);

        // Actualizar contraseña si se envía
        if (!empty($data['password'])) {
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            self::$userModel->updatePassword($id, $hashedPassword);
        }

        echo json_encode([
            'message' => 'Usuario actualizado',
            'id'      => (int)$id,
        ]);
    }

    /**
     * DELETE /api/users/{id}
     */
    private static function deleteUser($id) {
        $user = self::$userModel->findById($id);

        if (!$user) {
            http_response_code(404);
            echo json_encode(['error' => 'Usuario no encontrado']);
            return;
        }

        self::$userModel->delete($id);

        echo json_encode(['message' => 'Usuario eliminado']);
    }

    private static function methodNotAllowed() {
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
    }
}
