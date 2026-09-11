<?php $pageTitle = 'Ticket #' . $ticket['id']; ?>
<?php require_once BASE_PATH . '/app/Views/layouts/header.php'; ?>

<div class="page-header">
    <h2>Ticket #<?= $ticket['id'] ?> — <?= htmlspecialchars($ticket['title']) ?></h2>
    <a href="/tickets" class="btn">← Volver</a>
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
