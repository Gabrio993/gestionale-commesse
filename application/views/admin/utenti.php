<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utenti</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f6f8; color: #1f2937; }
        .wrap { max-width: 1100px; margin: 0 auto; padding: 32px 20px; }
        .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 16px; padding: 28px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { text-align: left; padding: 12px; border-bottom: 1px solid #e5e7eb; }
        th { background: #f9fafb; }
        a { display: inline-block; margin-top: 16px; text-decoration: none; color: #111827; font-weight: 700; }
        .badge { padding: 4px 10px; border-radius: 999px; background: #e5e7eb; font-size: 12px; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <h1>Utenti registrati</h1>
            <a href="<?= site_url('admin') ?>">Torna alla dashboard admin</a>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Ruolo</th>
                        <th>Attivo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (! empty($utenti)): ?>
                        <?php foreach ($utenti as $utente): ?>
                            <tr>
                                <td><?= (int) $utente->id ?></td>
                                <td><?= html_escape(trim($utente->nome . ' ' . $utente->cognome)) ?></td>
                                <td><?= html_escape($utente->email) ?></td>
                                <td><span class="badge"><?= html_escape($utente->ruolo) ?></span></td>
                                <td><?= (int) $utente->attivo ? 'Sì' : 'No' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Nessun utente trovato.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
