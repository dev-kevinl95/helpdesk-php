<?php

class TicketApiController {
    public static function handle() {
        header('Content-Type: application/json');

        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = rtrim($uri, '/');

        switch ($method) {
            case 'GET':
                echo json_encode(['message' => 'GET tickets - pendiente de implementar']);
                break;
            case 'POST':
                echo json_encode(['message' => 'POST ticket - pendiente de implementar']);
                break;
            default:
                http_response_code(405);
                echo json_encode(['error' => 'Método no permitido']);
        }
    }
}
