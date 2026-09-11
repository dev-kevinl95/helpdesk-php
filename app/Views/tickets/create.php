<?php $pageTitle = 'Crear Ticket'; ?>
<?php require_once BASE_PATH . '/app/Views/layouts/header.php'; ?>

<div class="page-header">
    <h2>Crear Ticket</h2>
    <a href="/tickets" class="btn">← Volver</a>
</div>

<?php if (!empty($error)): ?>
    <div class="error-msg"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card">
    <form method="POST" action="/tickets/create">
        <div class="form-group">
            <label>Título</label>
            <input type="text" name="title" required placeholder="Ej: Problema con el sistema de ventas"
                   value="<?= htmlspecialchars($_POST['title'] ?? '') ?>"
                   style="width:100%; padding:0.75rem; border:2px solid #ddd; border-radius:8px; font-size:1rem;">
        </div>

        <div class="form-group" style="margin-top:1rem;">
            <label>Descripción</label>
            <textarea name="description" required rows="5" placeholder="Describe el problema detalladamente..."
                      style="width:100%; padding:0.75rem; border:2px solid #ddd; border-radius:8px; font-size:1rem; resize:vertical;"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group" style="margin-top:1rem;">
            <label>Prioridad</label>
            <select name="priority" required style="width:100%; padding:0.75rem; border:2px solid #ddd; border-radius:8px; font-size:1rem;">
                <option value="">Seleccionar...</option>
                <option value="Baja" <?= ($_POST['priority'] ?? '') === 'Baja' ? 'selected' : '' ?>>Baja</option>
                <option value="Media" <?= ($_POST['priority'] ?? '') === 'Media' ? 'selected' : '' ?>>Media</option>
                <option value="Alta" <?= ($_POST['priority'] ?? '') === 'Alta' ? 'selected' : '' ?>>Alta</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top:1.5rem; width:100%;">Crear Ticket</button>
    </form>
</div>

<?php require_once BASE_PATH . '/app/Views/layouts/footer.php'; ?>
