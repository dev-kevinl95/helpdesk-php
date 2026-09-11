<?php $pageTitle = 'Editar Usuario'; ?>
<?php require_once BASE_PATH . '/app/Views/layouts/header.php'; ?>

<div class="page-header">
    <h2>Editar Usuario — <?= htmlspecialchars($user['name']) ?></h2>
    <a href="/admin/users" class="btn">← Volver</a>
</div>

<?php if (!empty($error)): ?>
    <div class="error-msg"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if (isset($_GET['updated'])): ?>
    <div class="success-msg">Usuario actualizado correctamente</div>
<?php endif; ?>

<div class="card">
    <form method="POST" action="/admin/users/<?= $user['id'] ?>/edit">
        <div class="form-group">
            <label>Nombre</label>
            <input type="text" name="name" required
                   value="<?= htmlspecialchars($user['name']) ?>"
                   style="width:100%; padding:0.75rem; border:2px solid #ddd; border-radius:8px; font-size:1rem;">
        </div>

        <div class="form-group" style="margin-top:1rem;">
            <label>Email</label>
            <input type="email" name="email" required
                   value="<?= htmlspecialchars($user['email']) ?>"
                   style="width:100%; padding:0.75rem; border:2px solid #ddd; border-radius:8px; font-size:1rem;">
        </div>

        <div class="form-group" style="margin-top:1rem;">
            <label>Nueva contraseña (dejar vacío para no cambiar)</label>
            <input type="password" name="password" placeholder="Dejar vacío si no cambia"
                   style="width:100%; padding:0.75rem; border:2px solid #ddd; border-radius:8px; font-size:1rem;">
        </div>

        <div class="form-group" style="margin-top:1rem;">
            <label>Rol</label>
            <select name="role" required style="width:100%; padding:0.75rem; border:2px solid #ddd; border-radius:8px; font-size:1rem;">
                <option value="Admin" <?= $user['role'] === 'Admin' ? 'selected' : '' ?>>Admin</option>
                <option value="Tecnico" <?= $user['role'] === 'Tecnico' ? 'selected' : '' ?>>Técnico</option>
                <option value="Cliente" <?= $user['role'] === 'Cliente' ? 'selected' : '' ?>>Cliente</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top:1.5rem; width:100%;">Guardar Cambios</button>
    </form>
</div>

<?php require_once BASE_PATH . '/app/Views/layouts/footer.php'; ?>
