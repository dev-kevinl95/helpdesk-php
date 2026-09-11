<?php $currentUser = AuthController::user(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Help Desk' ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f6fa;
            color: #333;
        }
        nav {
            background: #667eea;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin-left: 1.5rem;
            font-weight: 500;
        }
        nav a:hover { text-decoration: underline; }
        nav .brand { font-size: 1.2rem; font-weight: 700; }
        nav .user-info { font-size: 0.9rem; opacity: 0.9; }
        .container { max-width: 1100px; margin: 2rem auto; padding: 0 1rem; }
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .page-header h2 { color: #333; }
        .btn {
            display: inline-block;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            border: none;
            cursor: pointer;
        }
        .btn-primary { background: #667eea; color: white; }
        .btn-primary:hover { background: #5a6fd6; }
        .btn-success { background: #2e7d32; color: white; }
        .btn-success:hover { background: #256427; }
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 0.75rem 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th { background: #f8f9fa; font-weight: 600; color: #555; }
        tr:hover { background: #f8f9fa; }
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .badge-pendiente { background: #fff3e0; color: #e65100; }
        .badge-progreso { background: #e3f2fd; color: #1565c0; }
        .badge-resuelto { background: #e8f5e9; color: #2e7d32; }
        .badge-alta { background: #ffebee; color: #c62828; }
        .badge-media { background: #fff3e0; color: #e65100; }
        .badge-baja { background: #e8f5e9; color: #2e7d32; }
        .success-msg {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        .error-msg {
            background: #ffebee;
            color: #c62828;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }
        .stat-card .number { font-size: 2rem; font-weight: 700; color: #667eea; }
        .stat-card .label { color: #999; margin-top: 0.3rem; }
    </style>
</head>
<body>
    <nav>
        <div>
            <a href="/dashboard" class="brand">Help Desk</a>
            <a href="/tickets">Tickets</a>
            <?php if ($currentUser['role'] === 'Admin'): ?>
                <a href="/admin/users">Usuarios</a>
            <?php endif; ?>
        </div>
        <div class="user-info">
            <?= htmlspecialchars($currentUser['name']) ?> (<?= $currentUser['role'] ?>)
            <a href="/logout">Salir</a>
        </div>
    </nav>
    <div class="container">
