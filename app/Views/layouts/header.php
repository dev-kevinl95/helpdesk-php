<?php $currentUser = AuthController::user(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Help Desk' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@500;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <nav>
        <div class="nav-left">
            <a href="/dashboard" class="brand">Help Desk</a>
            <a href="/dashboard" class="<?= $_SERVER['REQUEST_URI'] === '/dashboard' ? 'active' : '' ?>">Dashboard</a>
            <a href="/tickets" class="<?= str_starts_with($_SERVER['REQUEST_URI'], '/tickets') ? 'active' : '' ?>">Tickets</a>
            <?php if ($currentUser['role'] === 'Admin'): ?>
                <a href="/admin/users" class="<?= str_starts_with($_SERVER['REQUEST_URI'], '/admin') ? 'active' : '' ?>">Usuarios</a>
            <?php endif; ?>
        </div>
        <div class="nav-right">
            <div class="user-info">
                <span class="user-name"><?= htmlspecialchars($currentUser['name']) ?></span>
                <span class="user-role"><?= $currentUser['role'] ?></span>
            </div>
            <div class="separator"></div>
            <a href="/profile">Mi perfil</a>
            <a href="/logout">Salir</a>
        </div>
    </nav>
    <div class="container">
