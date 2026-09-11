<?php

/**
 * Modelo: User
 * Maneja todo lo relacionado con la tabla users
 */
class User {
    private $db;

    public function __construct() {
        $this->db = getConnection();
    }

    /**
     * Buscar usuario por ID
     */
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT id, name, email, password, role, created_at FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Buscar usuario por email
     */
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    /**
     * Obtener todos los usuarios
     */
    public function findAll() {
        $stmt = $this->db->query("SELECT id, name, email, role, created_at FROM users ORDER BY id");
        return $stmt->fetchAll();
    }

    /**
     * Obtener todos los técnicos
     */
    public function findTechnicians() {
        $stmt = $this->db->query("SELECT id, name, email FROM users WHERE role = 'Tecnico' ORDER BY name");
        return $stmt->fetchAll();
    }

    /**
     * Crear usuario nuevo
     */
    public function create($name, $email, $password, $role) {
        $stmt = $this->db->prepare(
            "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)"
        );
        $stmt->execute([
            'name'     => $name,
            'email'    => $email,
            'password' => $password,
            'role'     => $role,
        ]);
        return $this->db->lastInsertId();
    }

    /**
     * Actualizar usuario
     */
    public function update($id, $data) {
        $fields = [];
        $params = ['id' => $id];

        foreach ($data as $key => $value) {
            if (in_array($key, ['name', 'email', 'role'])) {
                $fields[] = "$key = :$key";
                $params[$key] = $value;
            }
        }

        if (empty($fields)) return false;

        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Eliminar usuario
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Actualizar contraseña de usuario
     */
    public function updatePassword($id, $hashedPassword) {
        $stmt = $this->db->prepare("UPDATE users SET password = :password WHERE id = :id");
        return $stmt->execute(['password' => $hashedPassword, 'id' => $id]);
    }

    /**
     * Verificar si un email ya existe
     */
    public function emailExists($email) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetchColumn() > 0;
    }
}
