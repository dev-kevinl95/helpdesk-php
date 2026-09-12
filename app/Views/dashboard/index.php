<?php $pageTitle = 'Dashboard'; ?>
<?php require_once BASE_PATH . '/app/Views/layouts/header.php'; ?>

<div class="page-header page-header-light">
    <h2>Dashboard</h2>
    <a href="/tickets/create" class="btn btn-primary">+ Nuevo Ticket</a>
</div>

<div class="stats">
    <div class="stat-card">
        <div class="number"><?= $counts['Pendiente'] ?? 0 ?></div>
        <div class="label">Pendientes</div>
    </div>
    <div class="stat-card">
        <div class="number"><?= $counts['En progreso'] ?? 0 ?></div>
        <div class="label">En Progreso</div>
    </div>
    <div class="stat-card">
        <div class="number"><?= $counts['Resuelto'] ?? 0 ?></div>
        <div class="label">Resueltos</div>
    </div>
</div>

<div class="card card-light">
    <h3>Tickets Recientes</h3>

    <?php if (empty($recentTickets)): ?>
        <div class="empty-state">
            <div class="empty-icon">📋</div>
            <p>No hay tickets aún.</p>
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
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentTickets as $t): ?>
                    <tr>
                        <td><?= $t['id'] ?></td>
                        <td><a href="/tickets/<?= $t['id'] ?>"><?= htmlspecialchars($t['title']) ?></a></td>
                        <td><span class="badge badge-light-<?= strtolower($t['priority']) ?>"><?= $t['priority'] ?></span></td>
                        <td><span class="badge badge-light-<?= $t['status'] === 'Pendiente' ? 'pendiente' : ($t['status'] === 'En progreso' ? 'progreso' : 'resuelto') ?>"><?= $t['status'] ?></span></td>
                        <td><?= htmlspecialchars($t['created_by_name'] ?? 'N/A') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once BASE_PATH . '/app/Views/layouts/footer.php'; ?>
