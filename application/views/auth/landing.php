<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionale ore</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; min-height: 100vh; display: grid; place-items: center; background: #f4f6f8; color: #1f2937; }
        .card { width: min(92vw, 420px); background: #fff; border: 1px solid #e5e7eb; border-radius: 16px; padding: 28px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06); }
        h1 { margin-top: 0; }
        .actions { display: grid; gap: 12px; margin-top: 20px; }
        a.button { display: block; text-align: center; padding: 12px 16px; border-radius: 10px; text-decoration: none; font-weight: 700; }
        .primary { background: #111827; color: #fff; }
        .secondary { background: #e5e7eb; color: #111827; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Gestionale ore</h1>
        <p>Accedi con le tue credenziali oppure crea un nuovo account interno.</p>
        <div class="actions">
            <a class="button primary" href="<?= site_url('auth/login') ?>">Login</a>
            <a class="button secondary" href="<?= site_url('auth/registrati') ?>">Registrati</a>
        </div>
    </div>
</body>
</html>
