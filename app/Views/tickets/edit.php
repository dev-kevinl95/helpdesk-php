<?php $pageTitle = 'Editar Ticket #' . $ticket['id']; ?>
<?php require_once BASE_PATH . '/app/Views/layouts/header.php'; ?>

<div class="page-header page-header-light">
    <h2>Editar Ticket #<?= $ticket['id'] ?></h2>
    <a href="/tickets/<?= $ticket['id'] ?>" class="btn btn-ghost">← Volver</a>
</div>

<?php if (!empty($error)): ?>
    <div class="error-msg"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card card-light">
    <form method="POST" action="/tickets/<?= $ticket['id'] ?>/edit">
        <div class="form-group">
            <label>Título</label>
            <input type="text" name="title" required class="input"
                   value="<?= htmlspecialchars($ticket['title']) ?>">
        </div>

        <div class="form-group">
            <label>Descripción</label>
            <textarea name="description" required rows="5" class="textarea"><?= htmlspecialchars($ticket['description']) ?></textarea>
        </div>

        <div class="form-group">
            <label>Prioridad</label>
            <select name="priority" required class="select">
                <option value="Baja" <?= $ticket['priority'] === 'Baja' ? 'selected' : '' ?>>Baja</option>
                <option value="Media" <?= $ticket['priority'] === 'Media' ? 'selected' : '' ?>>Media</option>
                <option value="Alta" <?= $ticket['priority'] === 'Alta' ? 'selected' : '' ?>>Alta</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary btn-block" style="margin-top: 1rem;">Guardar Cambios</button>
    </form>
</div>

<?php require_once BASE_PATH . '/app/Views/layouts/footer.php'; ?>
