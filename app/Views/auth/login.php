<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Help Desk</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@500;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body class="login-page">
    <div class="login-box">
        <h1>Help Desk</h1>
        <p class="subtitle">Inicia sesión para continuar</p>

        <?php if (!empty($error)): ?>
            <div class="login-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="/login">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required
                       class="input"
                       placeholder="tu@email.com"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required
                       class="input"
                       placeholder="Tu contraseña">
            </div>

            <button type="submit">Iniciar Sesión</button>
        </form>
    </div>
    <script>
        const page = document.querySelector('.login-page');
        const before = page;
        let targetX = 0, targetY = 0, currentX = 0, currentY = 0;
        const maxShift = 15;
        const ease = 0.08;

        page.addEventListener('mousemove', (e) => {
            const cx = window.innerWidth / 2;
            const cy = window.innerHeight / 2;
            targetX = ((e.clientX - cx) / cx) * -maxShift;
            targetY = ((e.clientY - cy) / cy) * -maxShift;
        });

        page.addEventListener('mouseleave', () => {
            targetX = 0;
            targetY = 0;
        });

        function animate() {
            currentX += (targetX - currentX) * ease;
            currentY += (targetY - currentY) * ease;
            page.style.setProperty('--px', currentX + 'px');
            page.style.setProperty('--py', currentY + 'px');
            document.documentElement.style.setProperty('--login-px', currentX + 'px');
            document.documentElement.style.setProperty('--login-py', currentY + 'px');
            requestAnimationFrame(animate);
        }
        animate();
    </script>
</body>
</html>
