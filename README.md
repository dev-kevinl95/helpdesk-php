# Help Desk - Sistema de Gestión de Tickets

Sistema de gestión de tickets de soporte técnico. Flujo empresarial básico para administrar incidencias, asignar técnicos y dar seguimiento hasta su resolución.

## Stack

| Capa        | Tecnologías                    |
| ----------- | ------------------------------ |
| Backend     | PHP 8.5, POO, MVC, REST API   |
| Base de datos | PostgreSQL, SQL              |
| Frontend    | HTML5, CSS3, JavaScript        |
| Herramientas | Git, GitHub, Linux CLI, Postman |

## Flujo del Sistema

```
Usuario → Login → Dashboard → Crear Ticket → Asignar técnico → Cambiar estado → Agregar comentarios → Cerrar Ticket
```

## Tipos de Usuario

| Rol        | Descripción                                     |
| ---------- | ----------------------------------------------- |
| Admin      | Acceso total. Gestiona usuarios y ve todos los tickets. |
| Técnico    | Ve los tickets asignados y actualiza su estado. |
| Cliente    | Crea tickets y ve solo los suyos.               |

## Modelo de Datos

### Ticket

| Campo              | Tipo      | Descripción                        |
| ------------------ | --------- | ---------------------------------- |
| Título             | string    | Nombre del problema                |
| Descripción        | string    | Detalle del problema               |
| Prioridad          | enum      | `Baja` / `Media` / `Alta`         |
| Estado             | enum      | `Pendiente` / `En progreso` / `Resuelto` |
| Creado por         | relation  | Usuario que creó el ticket         |
| Técnico asignado   | relation  | Técnico encargado                  |
| Fecha de creación  | datetime  | Fecha y hora de creación           |

### Comentarios

Los comentarios permiten el seguimiento histórico dentro de cada ticket.

### Ejemplo de Ticket

```
Ticket #152 - Problema con sistema de ventas

Prioridad:  Alta
Estado:     En progreso
Asignado a: Kevin

Historial
--------------------------------
10:31  Ticket creado
10:45  Asignado a Kevin
11:02  Estado → En progreso
11:15  Kevin agregó un comentario
```

## Arquitectura del Proyecto

```
HelpDeskPhp/
├── app/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── TicketController.php
│   │   └── UserController.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Ticket.php
│   │   └── Comment.php
│   ├── Services/
│   │   └── TicketService.php
│   └── Views/
│       ├── layouts/      (header.php, footer.php)
│       ├── auth/         (login.php)
│       ├── tickets/      (index, create, edit, show)
│       ├── users/        (index)
│       └── dashboard/    (index)
├── config/
│   └── database.php
├── public/
│   ├── index.php         ← Punto de entrada
│   ├── css/
│   ├── js/
│   └── assets/
├── routes/
│   ├── web.php           ← Rutas de páginas
│   └── api.php           ← Rutas de API REST
├── database/
│   └── schema.sql
└── README.md
```

## Base de Datos

### Tablas

**users**

| Campo    | Tipo    | Descripción          |
| -------- | ------- | -------------------- |
| id       | int     | PK, autoincremental  |
| name     | string  | Nombre completo      |
| email    | string  | Correo electrónico   |
| password | string  | Contraseña (hash)    |
| role     | enum    | Admin / Técnico / Cliente |

**tickets**

| Campo        | Tipo    | Descripción              |
| ------------ | ------- | ------------------------ |
| id           | int     | PK, autoincremental      |
| title        | string  | Título del ticket        |
| description  | string  | Descripción detallada    |
| priority     | enum    | Baja / Media / Alta      |
| status       | enum    | Pendiente / En progreso / Resuelto |
| created_by   | int     | FK → users.id            |
| assigned_to  | int     | FK → users.id            |
| created_at   | datetime | Fecha de creación       |
| updated_at   | datetime | Fecha de última edición  |

**comments**

| Campo      | Tipo    | Descripción              |
| ---------- | ------- | ------------------------ |
| id         | int     | PK, autoincremental      |
| ticket_id  | int     | FK → tickets.id          |
| user_id    | int     | FK → users.id            |
| content    | string  | Texto del comentario     |
| created_at | datetime | Fecha de creación       |

### Relaciones

```
USER
 │
 ├──── creates ──────→ TICKET
 │                        │
 └──── comments ──────────┤
                          ↓
                       COMMENT
```

- `tickets.created_by` → `users.id`
- `tickets.assigned_to` → `users.id`
- `comments.ticket_id` → `tickets.id`
- `comments.user_id` → `users.id`

## API REST

Todas las respuestas son en formato JSON.

### Tickets

| Método   | Ruta                   | Descripción                  |
| -------- | ---------------------- | ---------------------------- |
| `GET`    | `/api/tickets`         | Listar todos los tickets     |
| `GET`    | `/api/tickets/:id`     | Obtener un ticket por ID     |
| `POST`   | `/api/tickets`         | Crear un ticket nuevo        |
| `PUT`    | `/api/tickets/:id`     | Actualizar un ticket         |
| `DELETE` | `/api/tickets/:id`     | Eliminar un ticket           |

### Filtros

| Método | Ruta                          | Descripción            |
| ------ | ----------------------------- | ---------------------- |
| `GET`  | `/api/tickets?status=pending` | Filtrar por estado     |
| `GET`  | `/api/tickets?priority=high`  | Filtrar por prioridad  |

### Relaciones

| Método | Ruta                          | Descripción               |
| ------ | ----------------------------- | ------------------------- |
| `GET`  | `/api/users/:id/tickets`      | Tickets de un usuario     |
| `POST` | `/api/tickets/:id/comments`   | Agregar comentario a ticket |
