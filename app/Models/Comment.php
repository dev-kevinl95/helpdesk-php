<?php

/**
 * Modelo: Comment
 * Maneja todo lo relacionado con la tabla comments
 */
class Comment {
    private $db;

    public function __construct() {
        $this->db = getConnection();
    }

    /**
     * Obtener todos los comentarios de un ticket
     */
    public function findByTicket($ticketId) {
        $stmt = $this->db->prepare("
            SELECT c.*, u.name AS user_name
            FROM comments c
            LEFT JOIN users u ON c.user_id = u.id
            WHERE c.ticket_id = :ticket_id
            ORDER BY c.created_at ASC
        ");
        $stmt->execute(['ticket_id' => $ticketId]);
        return $stmt->fetchAll();
    }

    /**
     * Obtener un comentario por ID
     */
    public function findById($id) {
        $stmt = $this->db->prepare("
            SELECT c.*, u.name AS user_name
            FROM comments c
            LEFT JOIN users u ON c.user_id = u.id
            WHERE c.id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Crear comentario nuevo
     */
    public function create($ticketId, $userId, $content) {
        $stmt = $this->db->prepare("
            INSERT INTO comments (ticket_id, user_id, content)
            VALUES (:ticket_id, :user_id, :content)
        ");
        $stmt->execute([
            'ticket_id' => $ticketId,
            'user_id'   => $userId,
            'content'   => $content,
        ]);
        return $this->db->lastInsertId();
    }

    /**
     * Eliminar comentario
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM comments WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Contar comentarios de un ticket
     */
    public function countByTicket($ticketId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM comments WHERE ticket_id = :ticket_id");
        $stmt->execute(['ticket_id' => $ticketId]);
        return $stmt->fetchColumn();
    }
}
