<?php $pageTitle = 'Crear Usuario'; ?>
<?php require_once BASE_PATH . '/app/Views/layouts/header.php'; ?>

<div class="page-header page-header-light">
    <h2>Crear Usuario</h2>
    <a href="/admin/users" class="btn btn-ghost">← Volver</a>
</div>

<?php if (!empty($error)): ?>
    <div class="error-msg"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card card-light">
    <form method="POST" action="/admin/users/create">
        <div class="form-group">
            <label>Nombre</label>
            <input type="text" name="name" required class="input"
                   placeholder="Nombre completo"
                   value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required class="input"
                   placeholder="usuario@email.com"
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label>Contraseña</label>
            <input type="password" name="password" required class="input"
                   placeholder="Mínimo 6 caracteres">
        </div>

        <div class="form-group">
            <label>Rol</label>
            <select name="role" required class="select">
                <option value="">Seleccionar...</option>
                <option value="Admin" <?= ($_POST['role'] ?? '') === 'Admin' ? 'selected' : '' ?>>Admin</option>
                <option value="Tecnico" <?= ($_POST['role'] ?? '') === 'Tecnico' ? 'selected' : '' ?>>Técnico</option>
                <option value="Cliente" <?= ($_POST['role'] ?? '') === 'Cliente' ? 'selected' : '' ?>>Cliente</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary btn-block" style="margin-top: 1rem;">Crear Usuario</button>
    </form>
</div>

<?php require_once BASE_PATH . '/app/Views/layouts/footer.php'; ?>
