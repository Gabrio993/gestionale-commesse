<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ruoli utenti</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f6f8; color: #1f2937; }
        .wrap { max-width: 1200px; margin: 0 auto; padding: 32px 20px; }
        .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 16px; padding: 28px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { text-align: left; padding: 12px; border-bottom: 1px solid #e5e7eb; vertical-align: top; }
        th { background: #f9fafb; }
        a { display: inline-block; margin-top: 16px; text-decoration: none; color: #111827; font-weight: 700; }
        .badge { padding: 4px 10px; border-radius: 999px; background: #e5e7eb; font-size: 12px; }
        select, button { padding: 10px; border-radius: 10px; border: 1px solid #d1d5db; }
        button { background: #111827; color: #fff; font-weight: 700; cursor: pointer; }
        form { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <h1>Gestione ruoli utenti</h1>
            <a href="<?= site_url('superadmin') ?>">Torna alla dashboard superadmin</a>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Ruolo attuale</th>
                        <th>Cambia ruolo</th>
                        <th>Ore</th>
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
                                <td>
                                    <form method="post" action="<?= site_url('superadmin/cambia-ruolo') ?>">
                                        <input type="hidden" name="utente_id" value="<?= (int) $utente->id ?>">
                                        <select name="ruolo">
                                            <option value="utente" <?= $utente->ruolo === 'utente' ? 'selected' : '' ?>>utente</option>
                                            <option value="admin" <?= $utente->ruolo === 'admin' ? 'selected' : '' ?>>admin</option>
                                            <option value="superadmin" <?= $utente->ruolo === 'superadmin' ? 'selected' : '' ?>>superadmin</option>
                                        </select>
                                        <button type="submit">Salva</button>
                                    </form>
                                </td>
                                <td><a href="<?= site_url('ore/utente/' . (int) $utente->id) ?>">Vedi ore</a></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">Nessun utente trovato.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
