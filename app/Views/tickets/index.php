<?php $pageTitle = 'Tickets'; ?>
<?php require_once BASE_PATH . '/app/Views/layouts/header.php'; ?>

<div class="page-header page-header-light">
    <h2>Tickets</h2>
    <a href="/tickets/create" class="btn btn-primary">+ Nuevo Ticket</a>
</div>

<?php if (isset($_GET['created'])): ?>
    <div class="success-msg">Ticket creado correctamente</div>
<?php endif; ?>

<div class="card card-light">
    <?php if (empty($tickets)): ?>
        <div class="empty-state">
            <div class="empty-icon">🎫</div>
            <p>No hay tickets creados ainda.</p>
            <a href="/tickets/create" class="btn btn-primary">Crear el primero</a>
        </div>
    <?php else: ?>
        <div class="table-wrapper-light">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Título</th>
                        <th>Prioridad</th>
                        <th>Estado</th>
                        <th>Creado por</th>
                        <th>Asignado a</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tickets as $t): ?>
                    <tr>
                        <td><?= $t['id'] ?></td>
                        <td><a href="/tickets/<?= $t['id'] ?>"><?= htmlspecialchars($t['title']) ?></a></td>
                        <td><span class="badge badge-light-<?= strtolower($t['priority']) ?>"><?= $t['priority'] ?></span></td>
                        <td><span class="badge badge-light-<?= $t['status'] === 'Pendiente' ? 'pendiente' : ($t['status'] === 'En progreso' ? 'progreso' : 'resuelto') ?>"><?= $t['status'] ?></span></td>
                        <td><?= htmlspecialchars($t['created_by_name'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($t['assigned_to_name'] ?? 'Sin asignar') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once BASE_PATH . '/app/Views/layouts/footer.php'; ?>
