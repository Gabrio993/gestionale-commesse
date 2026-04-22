<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dettaglio commessa</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f6f8; color: #1f2937; }
        .wrap { max-width: 1200px; margin: 0 auto; padding: 32px 20px; }
        .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 16px; padding: 28px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { text-align: left; padding: 12px; border-bottom: 1px solid #e5e7eb; vertical-align: top; }
        th { background: #f9fafb; }
        a { display: inline-block; margin-top: 16px; text-decoration: none; color: #111827; font-weight: 700; }
        .form-box { margin-top: 24px; padding: 20px; background: #f9fafb; border-radius: 12px; }
        label { display: block; margin: 10px 0 6px; font-weight: 700; }
        input, textarea { width: 100%; box-sizing: border-box; padding: 12px; border: 1px solid #d1d5db; border-radius: 10px; }
        textarea { min-height: 100px; }
        button { margin-top: 16px; padding: 12px 16px; border-radius: 10px; border: 0; background: #111827; color: #fff; font-weight: 700; cursor: pointer; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <h1><?= html_escape($commessa->codice) ?> - <?= html_escape($commessa->nome) ?></h1>
            <p>Cliente: <strong><?= html_escape($commessa->cliente_ragione_sociale) ?></strong></p>
            <p><?= html_escape($commessa->descrizione) ?></p>
            <a href="<?= site_url('commesse') ?>">Torna all'elenco</a>

            <div class="form-box">
                <h2>Inserisci ore</h2>
                <form method="post" action="<?= site_url('ore/salva') ?>">
                    <input type="hidden" name="commessa_id" value="<?= (int) $commessa->id ?>">

                    <label>Data lavoro</label>
                    <input type="date" name="data_lavoro" value="<?= date('Y-m-d') ?>" required>

                    <label>Ore</label>
                    <input type="number" name="ore" min="0" step="0.25" required>

                    <label>Note</label>
                    <textarea name="descrizione"></textarea>

                    <button type="submit">Salva ore</button>
                </form>
            </div>

            <h2>Registrazioni su questa commessa</h2>
            <table>
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Ore</th>
                        <th>Utente</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (! empty($ore_commessa)): ?>
                        <?php foreach ($ore_commessa as $riga): ?>
                            <tr>
                                <td><?= html_escape($riga->data_lavoro) ?></td>
                                <td><?= html_escape($riga->ore) ?></td>
                                <td><?= html_escape(trim($riga->nome . ' ' . $riga->cognome)) ?></td>
                                <td><?= html_escape($riga->descrizione) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">Nessuna registrazione presente.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
