<?php $pageTitle = 'Ticket #' . $ticket['id']; ?>
<?php require_once BASE_PATH . '/app/Views/layouts/header.php'; ?>

<?php
$canEdit = ($user['role'] === 'Admin') || ($user['role'] === 'Tecnico' && $ticket['assigned_to'] == $user['id']);
$canDelete = ($user['role'] === 'Admin');
?>

<?php if (isset($_GET['updated'])): ?>
    <div class="success-msg">Ticket actualizado correctamente</div>
<?php endif; ?>

<?php if (isset($_GET['assigned'])): ?>
    <div class="success-msg">Técnico asignado correctamente</div>
<?php endif; ?>

<?php if (isset($_GET['status_changed'])): ?>
    <div class="success-msg">Estado cambiado correctamente</div>
<?php endif; ?>

<div class="page-header page-header-light">
    <h2>Ticket #<?= $ticket['id'] ?> — <?= htmlspecialchars($ticket['title']) ?></h2>
    <div style="display: flex; gap: 0.5rem;">
        <?php if ($canEdit): ?>
            <a href="/tickets/<?= $ticket['id'] ?>/edit" class="btn btn-primary">Editar</a>
        <?php endif; ?>
        <a href="/tickets" class="btn btn-ghost">← Volver</a>
    </div>
</div>

<div class="card card-light">
    <div class="ticket-meta">
        <div class="meta-item">
            <span class="meta-label">Prioridad</span>
            <span class="meta-value"><span class="badge badge-light-<?= strtolower($ticket['priority']) ?>"><?= $ticket['priority'] ?></span></span>
        </div>
        <div class="meta-item">
            <span class="meta-label">Estado</span>
            <span class="meta-value"><span class="badge badge-light-<?= $ticket['status'] === 'Pendiente' ? 'pendiente' : ($ticket['status'] === 'En progreso' ? 'progreso' : 'resuelto') ?>"><?= $ticket['status'] ?></span></span>
        </div>
        <div class="meta-item">
            <span class="meta-label">Creado por</span>
            <span class="meta-value"><?= htmlspecialchars($ticket['created_by_name'] ?? 'N/A') ?></span>
        </div>
        <div class="meta-item">
            <span class="meta-label">Asignado a</span>
            <span class="meta-value"><?= htmlspecialchars($ticket['assigned_to_name'] ?? 'Sin asignar') ?></span>
        </div>
    </div>

    <?php if ($canEdit): ?>
    <div class="ticket-section">
        <strong>Cambiar estado</strong>
        <form method="POST" action="/tickets/<?= $ticket['id'] ?>/status" class="ticket-actions">
            <select name="status" class="select" style="width: auto;">
                <option value="Pendiente" <?= $ticket['status'] === 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                <option value="En progreso" <?= $ticket['status'] === 'En progreso' ? 'selected' : '' ?>>En progreso</option>
                <option value="Resuelto" <?= $ticket['status'] === 'Resuelto' ? 'selected' : '' ?>>Resuelto</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Cambiar</button>
        </form>
    </div>

    <div class="ticket-section">
        <strong>Asignar técnico</strong>
        <form method="POST" action="/tickets/<?= $ticket['id'] ?>/assign" class="ticket-actions">
            <select name="assigned_to" class="select" style="width: auto;">
                <option value="">Sin asignar</option>
                <?php foreach ($technicians as $tech): ?>
                    <option value="<?= $tech['id'] ?>" <?= ($ticket['assigned_to'] ?? '') == $tech['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($tech['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Asignar</button>
        </form>
    </div>
    <?php endif; ?>

    <div class="ticket-section">
        <strong>Descripción</strong>
        <p class="comment-body" style="margin-top: 0.5rem;">
            <?= nl2br(htmlspecialchars($ticket['description'])) ?>
        </p>
    </div>

    <div class="ticket-timestamps">
        <span>Creado: <?= $ticket['created_at'] ?></span>
        <span>Actualizado: <?= $ticket['updated_at'] ?></span>
    </div>
</div>

<?php if ($canDelete): ?>
<div class="card card-light danger-zone">
    <h3>Zona de peligro</h3>
    <form method="POST" action="/tickets/<?= $ticket['id'] ?>/delete"
          onsubmit="return confirm('¿Estás seguro de eliminar este ticket? Esta acción no se puede deshacer.');">
        <button type="submit" class="btn btn-danger btn-sm">Eliminar Ticket</button>
    </form>
</div>
<?php endif; ?>

<!-- Comentarios -->
<div class="card card-light">
    <h3>Comentarios (<?= count($comments) ?>)</h3>

    <?php if (empty($comments)): ?>
        <div class="empty-state">
            <div class="empty-icon">💬</div>
            <p>No hay comentarios aún. Sé el primero en comentar.</p>
        </div>
    <?php else: ?>
        <?php foreach ($comments as $c): ?>
            <div class="comment">
                <div class="comment-header">
                    <span class="comment-author"><?= htmlspecialchars($c['user_name'] ?? 'Usuario') ?></span>
                    <span class="comment-date"><?= $c['created_at'] ?></span>
                </div>
                <p class="comment-body"><?= nl2br(htmlspecialchars($c['content'])) ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Formulario de comentario -->
<div class="card card-light">
    <h3>Agregar Comentario</h3>
    <form method="POST" action="/tickets/<?= $ticket['id'] ?>/comment">
        <div class="form-group">
            <textarea name="content" required rows="3" class="textarea"
                      placeholder="Escribe tu comentario..."></textarea>
        </div>
        <button type="submit" class="btn btn-success">Enviar Comentario</button>
    </form>
</div>

<?php require_once BASE_PATH . '/app/Views/layouts/footer.php'; ?>
