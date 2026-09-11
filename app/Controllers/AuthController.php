<?php

require_once BASE_PATH . '/app/Models/User.php';

/**
 * Controlador: Auth
 * Maneja login, logout y sesiones
 */
class AuthController {

    /**
     * Mostrar formulario de login / procesar login
     */
    public static function login() {
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($email) || empty($password)) {
                $error = 'Ingresa tu email y contraseña';
            } else {
                $userModel = new User();
                $user = $userModel->findByEmail($email);

                // En producción se usaría password_verify() con hash
                if ($user && password_verify($password, $user['password'])) {
                    // Guardar datos en sesión
                    $_SESSION['user_id']   = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_role'] = $user['role'];

                    header('Location: /dashboard');
                    exit;
                } else {
                    $error = 'Email o contraseña incorrectos';
                }
            }
        }

        require_once BASE_PATH . '/app/Views/auth/login.php';
    }

    /**
     * Cerrar sesión
     */
    public static function logout() {
        session_destroy();
        header('Location: /login');
        exit;
    }

    /**
     * Verificar si el usuario está logueado
     * Si no está, redirigir al login
     */
    public static function requireAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    /**
     * Obtener el usuario actual de la sesión
     */
    public static function user() {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }
        return [
            'id'   => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'],
            'role' => $_SESSION['user_role'],
        ];
    }
}
