<?php

/**
 * Configuración de base de datos
 * PostgreSQL
 */

define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_NAME', 'helpdesk');
define('DB_USER', 'postgres');
define('DB_PASS', '123456');

/**
 * Crear conexión a PostgreSQL
 * Retorna la conexión PDO o muere con error
 */
function getConnection() {
    try {
        $dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        http_response_code(500);
        die(json_encode(['error' => 'Error de conexión: ' . $e->getMessage()]));
    }
}
