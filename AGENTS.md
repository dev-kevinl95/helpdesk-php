# AGENTS.md

## Project

PHP 8.5 Help Desk ticket management system. Greenfield — no code exists yet.

## Stack

- **Backend:** PHP 8.5, OOP, MVC pattern, REST API
- **Database:** PostgreSQL (SQL)
- **Frontend:** HTML5, CSS3, JavaScript
- **Tools:** Git, GitHub, Linux CLI, Postman

## Architecture

Entry point: `public/index.php`. Custom MVC framework (no Laravel/Symfony).

```
app/
├── Controllers/   # AuthController, TicketController, UserController
├── Models/        # User, Ticket, Comment
├── Services/      # TicketService
└── Views/         # PHP templates with layouts/
config/            # database.php
public/            # index.php, css/, js/, assets/
routes/            # web.php, api.php
database/          # schema.sql
```

## Key Conventions

- Three user roles: Admin, Técnico, Cliente
- Ticket states: Pendiente / En progreso / Resuelto
- Ticket priorities: Baja / Media / Alta
- Validation: priority ∈ {Baja, Media, Alta}, status ∈ {Pendiente, En progreso, Resuelto}
- REST API routes live in `routes/api.php`
- Web routes live in `routes/web.php`
- Entry flow: Login → Dashboard → Crear Ticket → Asignar técnico → Cambiar estado → Agregar comentarios → Cerrar Ticket

## Database

PostgreSQL. Three tables: `users`, `tickets`, `comments`. Schema in `database/schema.sql`.

Foreign keys with `ON DELETE SET NULL`:
- `tickets.created_by` → `users.id`
- `tickets.assigned_to` → `users.id`
- `comments.ticket_id` → `tickets.id`
- `comments.user_id` → `users.id`

## API

JSON responses. Example endpoints:
- `GET /api/tickets`, `GET /api/tickets/:id`, `POST /api/tickets`, `PUT /api/tickets/:id`, `DELETE /api/tickets/:id`
- `GET /api/tickets?status=pending`, `GET /api/tickets?priority=high`
- `GET /api/users/:id/tickets`, `POST /api/tickets/:id/comments`

## Auth & Roles

Session-based via `AuthController::login()`. Role enforcement in controllers (Admin sees all, Técnico sees assigned, Cliente sees own).
