<?php $pageTitle = 'Mi Perfil'; ?>
<?php require_once BASE_PATH . '/app/Views/layouts/header.php'; ?>

<div class="page-header">
    <h2>Mi Perfil</h2>
</div>

<div class="card">
    <div style="margin-bottom: 1.5rem;">
        <strong>Nombre:</strong> <?= htmlspecialchars($user['name']) ?>
    </div>
    <div style="margin-bottom: 1.5rem;">
        <strong>Rol:</strong> <?= $user['role'] ?>
    </div>
</div>

<div class="card">
    <h3 style="margin-bottom: 1rem;">Cambiar Contraseña</h3>

    <?php if (!empty($error)): ?>
        <div class="error-msg"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="success-msg"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" action="/profile">
        <div class="form-group">
            <label>Contraseña actual</label>
            <input type="password" name="current_password" required placeholder="Tu contraseña actual"
                   style="width:100%; padding:0.75rem; border:2px solid #ddd; border-radius:8px; font-size:1rem;">
        </div>

        <div class="form-group" style="margin-top:1rem;">
            <label>Nueva contraseña</label>
            <input type="password" name="new_password" required placeholder="Mínimo 6 caracteres"
                   style="width:100%; padding:0.75rem; border:2px solid #ddd; border-radius:8px; font-size:1rem;">
        </div>

        <div class="form-group" style="margin-top:1rem;">
            <label>Confirmar nueva contraseña</label>
            <input type="password" name="confirm_password" required placeholder="Repite la nueva contraseña"
                   style="width:100%; padding:0.75rem; border:2px solid #ddd; border-radius:8px; font-size:1rem;">
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top:1.5rem; width:100%;">Actualizar Contraseña</button>
    </form>
</div>

<?php require_once BASE_PATH . '/app/Views/layouts/footer.php'; ?>
