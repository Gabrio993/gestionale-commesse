<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; min-height: 100vh; background: #f4f6f8; color: #1f2937; }
        .wrap { max-width: 900px; margin: 0 auto; padding: 32px 20px; }
        .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 16px; padding: 28px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06); }
        a { display: inline-block; margin-top: 16px; padding: 12px 16px; border-radius: 10px; background: #111827; color: #fff; text-decoration: none; font-weight: 700; }
        .meta { color: #6b7280; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <h1>Area riservata</h1>
            <p>Benvenuto, <strong><?= html_escape($nome_utente ?? '') ?></strong>.</p>
            <p class="meta"><?= html_escape($email_utente ?? '') ?></p>
            <div class="grid" style="display:grid;gap:12px;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));">
                <a href="<?= site_url('commesse') ?>">Vedi commesse</a>
                <a href="<?= site_url('ore/mie') ?>">Le mie ore</a>
                <a href="<?= site_url('auth/logout') ?>">Logout</a>
            </div>
        </div>
    </div>
</body>
</html>
