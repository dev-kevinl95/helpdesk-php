<?php

require_once BASE_PATH . '/app/Models/User.php';
require_once BASE_PATH . '/app/Controllers/AuthController.php';

/**
 * Controlador: Admin
 * Gestión de usuarios (solo Admin)
 */
class AdminController {

    /**
     * Listar todos los usuarios
     */
    public static function users() {
        AuthController::requireAuth();
        self::requireAdmin();

        $userModel = new User();
        $users = $userModel->findAll();

        require_once BASE_PATH . '/app/Views/admin/users/index.php';
    }

    /**
     * Formulario para crear usuario
     */
    public static function createUser() {
        AuthController::requireAuth();
        self::requireAdmin();

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name     = trim($_POST['name'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $role     = $_POST['role'] ?? '';

            // Validar
            if (empty($name) || empty($email) || empty($password) || empty($role)) {
                $error = 'Todos los campos son obligatorios';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Email no válido';
            } elseif (strlen($password) < 6) {
                $error = 'La contraseña debe tener al menos 6 caracteres';
            } elseif (!in_array($role, ['Admin', 'Tecnico', 'Cliente'])) {
                $error = 'Rol no válido';
            } else {
                $userModel = new User();

                if ($userModel->emailExists($email)) {
                    $error = 'El email ya está registrado';
                } else {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $userModel->create($name, $email, $hashedPassword, $role);
                    header('Location: /admin/users?created=1');
                    exit;
                }
            }
        }

        require_once BASE_PATH . '/app/Views/admin/users/create.php';
    }

    /**
     * Formulario para editar usuario
     */
    public static function editUser($id) {
        AuthController::requireAuth();
        self::requireAdmin();

        $userModel = new User();
        $user = $userModel->findById($id);

        if (!$user) {
            header("Location: /admin/users");
            exit;
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name  = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $role  = $_POST['role'] ?? '';

            if (empty($name) || empty($email) || empty($role)) {
                $error = 'Todos los campos son obligatorios';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Email no válido';
            } elseif (!in_array($role, ['Admin', 'Tecnico', 'Cliente'])) {
                $error = 'Rol no válido';
            } else {
                // Verificar que el email no esté en uso por otro usuario
                $existing = $userModel->findByEmail($email);
                if ($existing && $existing['id'] != $id) {
                    $error = 'El email ya está en uso por otro usuario';
                } else {
                    $userModel->update($id, [
                        'name'  => $name,
                        'email' => $email,
                        'role'  => $role,
                    ]);

                    // Cambiar contraseña solo si se proporcionó
                    $newPassword = $_POST['password'] ?? '';
                    if (!empty($newPassword)) {
                        if (strlen($newPassword) < 6) {
                            $error = 'La contraseña debe tener al menos 6 caracteres';
                        } else {
                            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                            $userModel->updatePassword($id, $hashedPassword);
                        }
                    }

                    if (empty($error)) {
                        header("Location: /admin/users/$id?updated=1");
                        exit;
                    }
                }
            }
        }

        require_once BASE_PATH . '/app/Views/admin/users/edit.php';
    }

    /**
     * Eliminar usuario
     */
    public static function deleteUser($id) {
        AuthController::requireAuth();
        self::requireAdmin();

        // No permitir eliminarse a sí mismo
        $currentUser = AuthController::user();
        if ($currentUser['id'] == $id) {
            header("Location: /admin/users");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = new User();
            $userModel->delete($id);
            header("Location: /admin/users?deleted=1");
            exit;
        }

        header("Location: /admin/users");
        exit;
    }

    /**
     * Verificar que el usuario actual sea Admin
     */
    private static function requireAdmin() {
        $user = AuthController::user();
        if ($user['role'] !== 'Admin') {
            header("Location: /dashboard");
            exit;
        }
    }
}
