<?php

require_once BASE_PATH . '/app/Models/User.php';
require_once BASE_PATH . '/app/Controllers/AuthController.php';

/**
 * Controlador: Profile
 * Gestión de perfil del usuario actual
 */
class ProfileController {

    /**
     * Cambiar contraseña
     */
    public static function changePassword() {
        AuthController::requireAuth();

        $user = AuthController::user();
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword     = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                $error = 'Todos los campos son obligatorios';
            } elseif ($newPassword !== $confirmPassword) {
                $error = 'Las contraseñas nuevas no coinciden';
            } elseif (strlen($newPassword) < 6) {
                $error = 'La nueva contraseña debe tener al menos 6 caracteres';
            } else {
                $userModel = new User();
                $fullUser = $userModel->findById($user['id']);

                if (!password_verify($currentPassword, $fullUser['password'])) {
                    $error = 'La contraseña actual es incorrecta';
                } else {
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $userModel->updatePassword($user['id'], $hashedPassword);
                    $success = 'Contraseña actualizada correctamente';
                }
            }
        }

        require_once BASE_PATH . '/app/Views/profile/index.php';
    }
}
