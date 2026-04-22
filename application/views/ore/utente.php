<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ore utente</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f6f8; color: #1f2937; }
        .wrap { max-width: 1200px; margin: 0 auto; padding: 32px 20px; }
        .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 16px; padding: 28px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { text-align: left; padding: 12px; border-bottom: 1px solid #e5e7eb; vertical-align: top; }
        th { background: #f9fafb; }
        a { display: inline-block; margin-top: 16px; text-decoration: none; color: #111827; font-weight: 700; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <h1>Ore di <?= html_escape(trim($utente->nome . ' ' . $utente->cognome)) ?></h1>
            <p><?= html_escape($utente->email) ?></p>
            <a href="<?= site_url('admin/utenti') ?>">Torna alla lista utenti</a>

            <table>
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Ore</th>
                        <th>Commessa</th>
                        <th>Cliente</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (! empty($ore)): ?>
                        <?php foreach ($ore as $riga): ?>
                            <tr>
                                <td><?= html_escape($riga->data_lavoro) ?></td>
                                <td><?= html_escape($riga->ore) ?></td>
                                <td><?= html_escape($riga->commessa_codice . ' - ' . $riga->commessa_nome) ?></td>
                                <td><?= html_escape($riga->cliente_ragione_sociale) ?></td>
                                <td><?= html_escape($riga->descrizione) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Nessuna ora registrata per questo utente.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
