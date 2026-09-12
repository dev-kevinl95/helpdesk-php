<?php $pageTitle = 'Crear Ticket'; ?>
<?php require_once BASE_PATH . '/app/Views/layouts/header.php'; ?>

<div class="page-header page-header-light">
    <h2>Crear Ticket</h2>
    <a href="/tickets" class="btn btn-ghost">← Volver</a>
</div>

<?php if (!empty($error)): ?>
    <div class="error-msg"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card card-light">
    <form method="POST" action="/tickets/create">
        <div class="form-group">
            <label>Título</label>
            <input type="text" name="title" required class="input"
                   placeholder="Ej: Problema con el sistema de ventas"
                   value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label>Descripción</label>
            <textarea name="description" required rows="5" class="textarea"
                      placeholder="Describe el problema detalladamente..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label>Prioridad</label>
            <select name="priority" required class="select">
                <option value="">Seleccionar...</option>
                <option value="Baja" <?= ($_POST['priority'] ?? '') === 'Baja' ? 'selected' : '' ?>>Baja</option>
                <option value="Media" <?= ($_POST['priority'] ?? '') === 'Media' ? 'selected' : '' ?>>Media</option>
                <option value="Alta" <?= ($_POST['priority'] ?? '') === 'Alta' ? 'selected' : '' ?>>Alta</option>
            </select>
        </div>

        <?php if (!empty($technicians)): ?>
        <div class="form-group">
            <label>Asignar a (opcional)</label>
            <select name="assigned_to" class="select">
                <option value="">Sin asignar</option>
                <?php foreach ($technicians as $tech): ?>
                    <option value="<?= $tech['id'] ?>" <?= ($_POST['assigned_to'] ?? '') == $tech['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($tech['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>

        <button type="submit" class="btn btn-primary btn-block" style="margin-top: 1rem;">Crear Ticket</button>
    </form>
</div>

<?php require_once BASE_PATH . '/app/Views/layouts/footer.php'; ?>
