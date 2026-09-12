<?php $pageTitle = 'Editar Usuario'; ?>
<?php require_once BASE_PATH . '/app/Views/layouts/header.php'; ?>

<div class="page-header page-header-light">
    <h2>Editar Usuario — <?= htmlspecialchars($user['name']) ?></h2>
    <a href="/admin/users" class="btn btn-ghost">← Volver</a>
</div>

<?php if (!empty($error)): ?>
    <div class="error-msg"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if (isset($_GET['updated'])): ?>
    <div class="success-msg">Usuario actualizado correctamente</div>
<?php endif; ?>

<div class="card card-light">
    <form method="POST" action="/admin/users/<?= $user['id'] ?>/edit">
        <div class="form-group">
            <label>Nombre</label>
            <input type="text" name="name" required class="input"
                   value="<?= htmlspecialchars($user['name']) ?>">
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required class="input"
                   value="<?= htmlspecialchars($user['email']) ?>">
        </div>

        <div class="form-group">
            <label>Nueva contraseña (dejar vacío para no cambiar)</label>
            <input type="password" name="password" class="input"
                   placeholder="Dejar vacío si no cambia">
        </div>

        <div class="form-group">
            <label>Rol</label>
            <select name="role" required class="select">
                <option value="Admin" <?= $user['role'] === 'Admin' ? 'selected' : '' ?>>Admin</option>
                <option value="Tecnico" <?= $user['role'] === 'Tecnico' ? 'selected' : '' ?>>Técnico</option>
                <option value="Cliente" <?= $user['role'] === 'Cliente' ? 'selected' : '' ?>>Cliente</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary btn-block" style="margin-top: 1rem;">Guardar Cambios</button>
    </form>
</div>

<?php require_once BASE_PATH . '/app/Views/layouts/footer.php'; ?>
