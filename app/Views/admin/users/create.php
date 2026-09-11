<?php $pageTitle = 'Crear Usuario'; ?>
<?php require_once BASE_PATH . '/app/Views/layouts/header.php'; ?>

<div class="page-header">
    <h2>Crear Usuario</h2>
    <a href="/admin/users" class="btn">← Volver</a>
</div>

<?php if (!empty($error)): ?>
    <div class="error-msg"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card">
    <form method="POST" action="/admin/users/create">
        <div class="form-group">
            <label>Nombre</label>
            <input type="text" name="name" required placeholder="Nombre completo"
                   value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                   style="width:100%; padding:0.75rem; border:2px solid #ddd; border-radius:8px; font-size:1rem;">
        </div>

        <div class="form-group" style="margin-top:1rem;">
            <label>Email</label>
            <input type="email" name="email" required placeholder="usuario@email.com"
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                   style="width:100%; padding:0.75rem; border:2px solid #ddd; border-radius:8px; font-size:1rem;">
        </div>

        <div class="form-group" style="margin-top:1rem;">
            <label>Contraseña</label>
            <input type="password" name="password" required placeholder="Mínimo 6 caracteres"
                   style="width:100%; padding:0.75rem; border:2px solid #ddd; border-radius:8px; font-size:1rem;">
        </div>

        <div class="form-group" style="margin-top:1rem;">
            <label>Rol</label>
            <select name="role" required style="width:100%; padding:0.75rem; border:2px solid #ddd; border-radius:8px; font-size:1rem;">
                <option value="">Seleccionar...</option>
                <option value="Admin" <?= ($_POST['role'] ?? '') === 'Admin' ? 'selected' : '' ?>>Admin</option>
                <option value="Tecnico" <?= ($_POST['role'] ?? '') === 'Tecnico' ? 'selected' : '' ?>>Técnico</option>
                <option value="Cliente" <?= ($_POST['role'] ?? '') === 'Cliente' ? 'selected' : '' ?>>Cliente</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top:1.5rem; width:100%;">Crear Usuario</button>
    </form>
</div>

<?php require_once BASE_PATH . '/app/Views/layouts/footer.php'; ?>
