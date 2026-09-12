<?php $pageTitle = 'Mi Perfil'; ?>
<?php require_once BASE_PATH . '/app/Views/layouts/header.php'; ?>

<div class="page-header page-header-light">
    <h2>Mi Perfil</h2>
</div>

<div class="card card-light">
    <div style="display: flex; gap: 2rem; margin-bottom: 0.5rem;">
        <div>
            <span style="font-size: 0.8rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.04em;">Nombre</span>
            <p style="font-size: 1rem; margin-top: 0.2rem; color: #1f2937;"><?= htmlspecialchars($user['name']) ?></p>
        </div>
        <div>
            <span style="font-size: 0.8rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.04em;">Rol</span>
            <p style="font-size: 1rem; margin-top: 0.2rem; color: #1f2937;"><?= $user['role'] ?></p>
        </div>
    </div>
</div>

<div class="card card-light">
    <h3>Cambiar Contraseña</h3>

    <?php if (!empty($error)): ?>
        <div class="error-msg"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="success-msg"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" action="/profile">
        <div class="form-group">
            <label>Contraseña actual</label>
            <input type="password" name="current_password" required class="input"
                   placeholder="Tu contraseña actual">
        </div>

        <div class="form-group">
            <label>Nueva contraseña</label>
            <input type="password" name="new_password" required class="input"
                   placeholder="Mínimo 6 caracteres">
        </div>

        <div class="form-group">
            <label>Confirmar nueva contraseña</label>
            <input type="password" name="confirm_password" required class="input"
                   placeholder="Repite la nueva contraseña">
        </div>

        <button type="submit" class="btn btn-primary btn-block" style="margin-top: 1rem;">Actualizar Contraseña</button>
    </form>
</div>

<?php require_once BASE_PATH . '/app/Views/layouts/footer.php'; ?>
