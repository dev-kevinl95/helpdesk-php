<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help Desk</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #333;
        }
        .card {
            background: white;
            padding: 3rem 4rem;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        h1 { font-size: 2rem; margin-bottom: 0.5rem; color: #667eea; }
        p { color: #666; margin-top: 0.5rem; }
        .status {
            margin-top: 1.5rem;
            padding: 0.75rem 1.5rem;
            background: #e8f5e9;
            color: #2e7d32;
            border-radius: 8px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>Help Desk</h1>
        <p>Sistema de Gestión de Tickets de Soporte</p>
        <div class="status">Servidor funcionando correctamente</div>
    </div>
</body>
</html>
