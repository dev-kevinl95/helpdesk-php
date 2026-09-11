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

<div class="page-header">
    <h2>Ticket #<?= $ticket['id'] ?> — <?= htmlspecialchars($ticket['title']) ?></h2>
    <div>
        <?php if ($canEdit): ?>
            <a href="/tickets/<?= $ticket['id'] ?>/edit" class="btn btn-primary">Editar</a>
        <?php endif; ?>
        <a href="/tickets" class="btn">← Volver</a>
    </div>
</div>

<div class="card">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
        <div>
            <strong>Prioridad:</strong>
            <span class="badge badge-<?= strtolower($ticket['priority']) ?>"><?= $ticket['priority'] ?></span>
        </div>
        <div>
            <strong>Estado:</strong>
            <span class="badge badge-<?= $ticket['status'] === 'Pendiente' ? 'pendiente' : ($ticket['status'] === 'En progreso' ? 'progreso' : 'resuelto') ?>"><?= $ticket['status'] ?></span>
        </div>
        <div>
            <strong>Creado por:</strong> <?= htmlspecialchars($ticket['created_by_name'] ?? 'N/A') ?>
        </div>
        <div>
            <strong>Asignado a:</strong> <?= htmlspecialchars($ticket['assigned_to_name'] ?? 'Sin asignar') ?>
        </div>
    </div>

    <?php if ($canEdit): ?>
    <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid #eee;">
        <strong>Cambiar estado:</strong>
        <form method="POST" action="/tickets/<?= $ticket['id'] ?>/status" style="display: flex; gap: 0.5rem; margin-top: 0.5rem; align-items: center;">
            <select name="status" style="padding: 0.5rem 1rem; border: 2px solid #ddd; border-radius: 8px; font-size: 0.9rem;">
                <option value="Pendiente" <?= $ticket['status'] === 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                <option value="En progreso" <?= $ticket['status'] === 'En progreso' ? 'selected' : '' ?>>En progreso</option>
                <option value="Resuelto" <?= $ticket['status'] === 'Resuelto' ? 'selected' : '' ?>>Resuelto</option>
            </select>
            <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem;">Cambiar</button>
        </form>
    </div>

    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #eee;">
        <strong>Asignar técnico:</strong>
        <form method="POST" action="/tickets/<?= $ticket['id'] ?>/assign" style="display: flex; gap: 0.5rem; margin-top: 0.5rem; align-items: center;">
            <select name="assigned_to" style="padding: 0.5rem 1rem; border: 2px solid #ddd; border-radius: 8px; font-size: 0.9rem;">
                <option value="">Sin asignar</option>
                <?php foreach ($technicians as $tech): ?>
                    <option value="<?= $tech['id'] ?>" <?= ($ticket['assigned_to'] ?? '') == $tech['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($tech['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem;">Asignar</button>
        </form>
    </div>
    <?php endif; ?>

    <div style="margin-top: 1rem;">
        <strong>Descripción:</strong>
        <p style="margin-top: 0.5rem; color: #555; line-height: 1.6;">
            <?= nl2br(htmlspecialchars($ticket['description'])) ?>
        </p>
    </div>

    <div style="margin-top: 1rem; color: #999; font-size: 0.85rem;">
        Creado: <?= $ticket['created_at'] ?> |
        Actualizado: <?= $ticket['updated_at'] ?>
    </div>
</div>

<?php if ($canDelete): ?>
<div class="card" style="border: 2px solid #ffebee;">
    <h3 style="margin-bottom: 1rem; color: #c62828;">Zona de peligro</h3>
    <form method="POST" action="/tickets/<?= $ticket['id'] ?>/delete"
          onsubmit="return confirm('¿Estás seguro de eliminar este ticket? Esta acción no se puede deshacer.');">
        <button type="submit" class="btn" style="background: #c62828; color: white; padding: 0.6rem 1.5rem;">Eliminar Ticket</button>
    </form>
</div>
<?php endif; ?>

<!-- Comentarios -->
<div class="card">
    <h3 style="margin-bottom: 1rem;">Comentarios (<?= count($comments) ?>)</h3>

    <?php if (empty($comments)): ?>
        <p style="color: #999;">No hay comentarios aún</p>
    <?php else: ?>
        <?php foreach ($comments as $c): ?>
            <div style="border-bottom: 1px solid #eee; padding: 1rem 0;">
                <strong><?= htmlspecialchars($c['user_name'] ?? 'Usuario') ?></strong>
                <span style="color: #999; font-size: 0.85rem; margin-left: 0.5rem;"><?= $c['created_at'] ?></span>
                <p style="margin-top: 0.5rem; color: #555;"><?= nl2br(htmlspecialchars($c['content'])) ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Formulario de comentario -->
<div class="card">
    <h3 style="margin-bottom: 1rem;">Agregar Comentario</h3>
    <form method="POST" action="/tickets/<?= $ticket['id'] ?>/comment">
        <textarea name="content" required rows="3" placeholder="Escribe tu comentario..."
                  style="width:100%; padding:0.75rem; border:2px solid #ddd; border-radius:8px; font-size:1rem; resize:vertical;"></textarea>
        <button type="submit" class="btn btn-success" style="margin-top: 0.75rem;">Enviar Comentario</button>
    </form>
</div>

<?php require_once BASE_PATH . '/app/Views/layouts/footer.php'; ?>
