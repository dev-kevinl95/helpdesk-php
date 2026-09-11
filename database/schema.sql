-- ============================================
-- Help Desk - Schema de Base de Datos
-- PostgreSQL
-- Seguro para ejecutar múltiples veces
-- ============================================

-- ============================================
-- Tabla: users
-- Solo se crea si no existe
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL CHECK (role IN ('Admin', 'Tecnico', 'Cliente')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- Tabla: tickets
-- Solo se crea si no existe
-- ============================================
CREATE TABLE IF NOT EXISTS tickets (
    id SERIAL PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    priority VARCHAR(10) NOT NULL CHECK (priority IN ('Baja', 'Media', 'Alta')),
    status VARCHAR(20) NOT NULL DEFAULT 'Pendiente' CHECK (status IN ('Pendiente', 'En progreso', 'Resuelto')),
    created_by INTEGER NOT NULL,
    assigned_to INTEGER,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_tickets_created_by
        FOREIGN KEY (created_by)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_tickets_assigned_to
        FOREIGN KEY (assigned_to)
        REFERENCES users(id)
        ON DELETE SET NULL
);

-- ============================================
-- Tabla: comments
-- Solo se crea si no existe
-- ============================================
CREATE TABLE IF NOT EXISTS comments (
    id SERIAL PRIMARY KEY,
    ticket_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_comments_ticket
        FOREIGN KEY (ticket_id)
        REFERENCES tickets(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_comments_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE SET NULL
);

-- ============================================
-- Indices para mejorar rendimiento
-- Solo se crean si no existen
-- ============================================
CREATE INDEX IF NOT EXISTS idx_tickets_status ON tickets(status);
CREATE INDEX IF NOT EXISTS idx_tickets_priority ON tickets(priority);
CREATE INDEX IF NOT EXISTS idx_tickets_created_by ON tickets(created_by);
CREATE INDEX IF NOT EXISTS idx_tickets_assigned_to ON tickets(assigned_to);
CREATE INDEX IF NOT EXISTS idx_comments_ticket_id ON comments(ticket_id);

-- ============================================
-- Datos de prueba
-- Solo se insertan si la tabla está vacía
-- ============================================
INSERT INTO users (name, email, password, role)
SELECT 'Admin Principal', 'admin@helpdesk.com', 'admin123', 'Admin'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = 'admin@helpdesk.com');

INSERT INTO users (name, email, password, role)
SELECT 'Kevin Tecnico', 'kevin@helpdesk.com', 'tecnico123', 'Tecnico'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = 'kevin@helpdesk.com');

INSERT INTO users (name, email, password, role)
SELECT 'Cliente Ejemplo', 'cliente@ejemplo.com', 'cliente123', 'Cliente'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = 'cliente@ejemplo.com');
