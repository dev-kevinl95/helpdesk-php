<?php

/**
 * Modelo: Ticket
 * Maneja todo lo relacionado con la tabla tickets
 */
class Ticket {
    private $db;

    public function __construct() {
        $this->db = getConnection();
    }

    /**
     * Buscar ticket por ID (con info del usuario creador y técnico)
     */
    public function findById($id) {
        $stmt = $this->db->prepare("
            SELECT t.*,
                   u1.name AS created_by_name,
                   u2.name AS assigned_to_name
            FROM tickets t
            LEFT JOIN users u1 ON t.created_by = u1.id
            LEFT JOIN users u2 ON t.assigned_to = u2.id
            WHERE t.id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Obtener todos los tickets (con nombres de usuario)
     */
    public function findAll() {
        $stmt = $this->db->query("
            SELECT t.*,
                   u1.name AS created_by_name,
                   u2.name AS assigned_to_name
            FROM tickets t
            LEFT JOIN users u1 ON t.created_by = u1.id
            LEFT JOIN users u2 ON t.assigned_to = u2.id
            ORDER BY t.id DESC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Filtrar tickets por estado
     */
    public function findByStatus($status) {
        $stmt = $this->db->prepare("
            SELECT t.*,
                   u1.name AS created_by_name,
                   u2.name AS assigned_to_name
            FROM tickets t
            LEFT JOIN users u1 ON t.created_by = u1.id
            LEFT JOIN users u2 ON t.assigned_to = u2.id
            WHERE t.status = :status
            ORDER BY t.id DESC
        ");
        $stmt->execute(['status' => $status]);
        return $stmt->fetchAll();
    }

    /**
     * Filtrar tickets por prioridad
     */
    public function findByPriority($priority) {
        $stmt = $this->db->prepare("
            SELECT t.*,
                   u1.name AS created_by_name,
                   u2.name AS assigned_to_name
            FROM tickets t
            LEFT JOIN users u1 ON t.created_by = u1.id
            LEFT JOIN users u2 ON t.assigned_to = u2.id
            WHERE t.priority = :priority
            ORDER BY t.id DESC
        ");
        $stmt->execute(['priority' => $priority]);
        return $stmt->fetchAll();
    }

    /**
     * Obtener tickets de un usuario específico
     */
    public function findByUser($userId) {
        $stmt = $this->db->prepare("
            SELECT t.*,
                   u1.name AS created_by_name,
                   u2.name AS assigned_to_name
            FROM tickets t
            LEFT JOIN users u1 ON t.created_by = u1.id
            LEFT JOIN users u2 ON t.assigned_to = u2.id
            WHERE t.created_by = :userId OR t.assigned_to = :userId
            ORDER BY t.id DESC
        ");
        $stmt->execute(['userId' => $userId]);
        return $stmt->fetchAll();
    }

    /**
     * Crear ticket nuevo
     */
    public function create($title, $description, $priority, $createdBy) {
        $stmt = $this->db->prepare("
            INSERT INTO tickets (title, description, priority, status, created_by)
            VALUES (:title, :description, :priority, 'Pendiente', :created_by)
        ");
        $stmt->execute([
            'title'       => $title,
            'description' => $description,
            'priority'    => $priority,
            'created_by'  => $createdBy,
        ]);
        return $this->db->lastInsertId();
    }

    /**
     * Actualizar ticket
     */
    public function update($id, $data) {
        $fields = [];
        $params = ['id' => $id];

        foreach ($data as $key => $value) {
            if (in_array($key, ['title', 'description', 'priority', 'status', 'assigned_to'])) {
                $fields[] = "$key = :$key";
                $params[$key] = $value;
            }
        }

        if (empty($fields)) return false;

        $fields[] = "updated_at = CURRENT_TIMESTAMP";
        $sql = "UPDATE tickets SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Eliminar ticket
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM tickets WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Contar tickets por estado
     */
    public function countByStatus() {
        $stmt = $this->db->query("
            SELECT status, COUNT(*) AS total
            FROM tickets
            GROUP BY status
        ");
        $result = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        return $result;
    }
}
