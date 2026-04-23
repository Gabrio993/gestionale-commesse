<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; min-height: 100vh; display: grid; place-items: center; background: #f4f6f8; color: #1f2937; }
        .card { width: min(92vw, 420px); background: #fff; border: 1px solid #e5e7eb; border-radius: 16px; padding: 28px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06); }
        label { display: block; margin: 14px 0 6px; font-weight: 700; }
        input { width: 100%; box-sizing: border-box; padding: 12px; border: 1px solid #d1d5db; border-radius: 10px; }
        button, .link { display: inline-block; margin-top: 16px; padding: 12px 16px; border-radius: 10px; border: 0; text-decoration: none; font-weight: 700; cursor: pointer; }
        button { background: #111827; color: #fff; }
        .link { background: #e5e7eb; color: #111827; }
        .msg { margin: 12px 0 0; color: #065f46; }
        .err { margin: 12px 0 0; color: #b91c1c; }
    </style>
</head>
<body>
    <!-- Form di login: dopo l'autenticazione il controller decide la dashboard corretta in base al ruolo. -->
    <div class="card">
        <h1>Login</h1>
        <p>Entra con la tua email e password.</p>

        <?php if (isset($messaggio)): ?>
            <div class="msg"><?= html_escape($messaggio) ?></div>
        <?php endif; ?>

        <?php if (isset($errore)): ?>
            <div class="err"><?= html_escape($errore) ?></div>
        <?php endif; ?>

        <?= validation_errors('<div class="err">', '</div>') ?>

        <form method="post" action="<?= site_url('auth/autentica') ?>">
            <label>Email</label>
            <input type="email" name="email" value="<?= set_value('email') ?>" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit">Accedi</button>
            <a class="link" href="<?= site_url('auth/registrati') ?>">Registrati</a>
        </form>
    </div>
</body>
</html>
