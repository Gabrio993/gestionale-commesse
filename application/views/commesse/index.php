<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commesse</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f6f8; color: #1f2937; }
        .wrap { max-width: 1200px; margin: 0 auto; padding: 32px 20px; }
        .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 16px; padding: 28px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { text-align: left; padding: 12px; border-bottom: 1px solid #e5e7eb; vertical-align: top; }
        th { background: #f9fafb; }
        a { display: inline-block; margin-top: 16px; text-decoration: none; color: #111827; font-weight: 700; }
        .badge { padding: 4px 10px; border-radius: 999px; background: #e5e7eb; font-size: 12px; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <h1>Commesse</h1>
            <?php if (in_array($this->session->userdata('utente_ruolo'), array('admin', 'superadmin'), true)): ?>
                <a href="<?= site_url('commesse/nuova') ?>">Nuova commessa</a>
                <a href="<?= site_url('admin') ?>">Torna indietro</a>
            <?php else: ?>
                <a href="<?= site_url('dashboard') ?>">Torna alla dashboard</a>
            <?php endif; ?>
            <a href="<?= site_url('ore/mie') ?>">Le mie ore</a>

            <table>
                <thead>
                    <tr>
                        <th>Codice</th>
                        <th>Nome</th>
                        <th>Cliente</th>
                        <th>Stato</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (! empty($commesse)): ?>
                        <?php foreach ($commesse as $commessa): ?>
                            <tr>
                                <td><?= html_escape($commessa->codice) ?></td>
                                <td><?= html_escape($commessa->nome) ?></td>
                                <td><?= html_escape($commessa->cliente_ragione_sociale) ?></td>
                                <td><span class="badge"><?= html_escape($commessa->stato) ?></span></td>
                                <td><a href="<?= site_url('commesse/dettaglio/' . (int) $commessa->id) ?>">Apri</a></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Nessuna commessa presente.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
