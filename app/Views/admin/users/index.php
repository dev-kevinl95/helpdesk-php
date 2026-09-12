<?php $pageTitle = 'Gestionar Usuarios'; ?>
<?php require_once BASE_PATH . '/app/Views/layouts/header.php'; ?>

<?php if (isset($_GET['created'])): ?>
    <div class="success-msg">Usuario creado correctamente</div>
<?php endif; ?>

<?php if (isset($_GET['deleted'])): ?>
    <div class="success-msg">Usuario eliminado correctamente</div>
<?php endif; ?>

<div class="page-header page-header-light">
    <h2>Usuarios</h2>
    <a href="/admin/users/create" class="btn btn-primary">+ Nuevo Usuario</a>
</div>

<div class="card card-light">
    <div class="table-wrapper-light">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['name']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td>
                        <span class="badge badge-light-<?= $u['role'] === 'Admin' ? 'admin' : ($u['role'] === 'Tecnico' ? 'tecnico' : 'cliente') ?>">
                            <?= $u['role'] ?>
                        </span>
                    </td>
                    <td style="display: flex; gap: 0.4rem;">
                        <a href="/admin/users/<?= $u['id'] ?>/edit" class="btn btn-primary btn-sm">Editar</a>
                        <?php if ($u['id'] != $_SESSION['user_id']): ?>
                            <form method="POST" action="/admin/users/<?= $u['id'] ?>/delete" style="display:inline;" onsubmit="return confirm('¿Eliminar este usuario?');">
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once BASE_PATH . '/app/Views/layouts/footer.php'; ?>
