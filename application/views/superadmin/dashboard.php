<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superadmin</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; min-height: 100vh; background: #f4f6f8; color: #1f2937; }
        .wrap { max-width: 1000px; margin: 0 auto; padding: 32px 20px; }
        .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 16px; padding: 28px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06); }
        .grid { display: grid; gap: 16px; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); margin-top: 20px; }
        a.button { display: block; text-decoration: none; padding: 16px; border-radius: 12px; background: #111827; color: #fff; font-weight: 700; }
        .meta { color: #6b7280; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <h1>Area superadmin</h1>
            <p>Benvenuto, <strong><?= html_escape($nome_utente ?? '') ?></strong>.</p>
            <p class="meta"><?= html_escape($email_utente ?? '') ?></p>

            <div class="grid">
                <a class="button" href="<?= site_url('clienti') ?>">Gestione clienti</a>
                <a class="button" href="<?= site_url('clienti/nuovo') ?>">Nuovo cliente</a>
                <a class="button" href="<?= site_url('commesse') ?>">Gestione commesse</a>
                <a class="button" href="<?= site_url('commesse/nuova') ?>">Nuova commessa</a>
                <a class="button" href="<?= site_url('superadmin/utenti') ?>">Gestione ruoli utenti</a>
                <a class="button" href="<?= site_url('auth/logout') ?>">Logout</a>
            </div>
        </div>
    </div>
</body>
</html>
