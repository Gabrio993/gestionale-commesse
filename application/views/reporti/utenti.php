<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report utenti</title>
    <?php $this->load->view('partials/ui'); ?>
</head>
<body>
    <?php $this->load->view('partials/navigation'); ?>

    <div class="app-wrap">
        <div class="app-card">
            <div class="page-head">
                <div>
                    <h1 class="page-title"><?= html_escape($titolo) ?></h1>
                    <p class="page-subtitle">Confronto ore tra utenti registrati.</p>
                </div>
                <div class="actions-inline">
                    <a class="btn secondary" href="<?= site_url('reporti') ?>">Report generale</a>
                </div>
            </div>

            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Utente</th>
                            <th>Email</th>
                            <th>Ruolo</th>
                            <th>Ore totali</th>
                            <th>Dettaglio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (! empty($riepilogo_utenti)): ?>
                            <?php foreach ($riepilogo_utenti as $riga): ?>
                                <tr>
                                    <td><?= html_escape(trim($riga->nome . ' ' . $riga->cognome)) ?></td>
                                    <td><?= html_escape($riga->email) ?></td>
                                    <td><span class="badge"><?= html_escape($riga->ruolo) ?></span></td>
                                    <td><strong><?= number_format((float) $riga->totale_ore, 2, ',', '.') ?></strong></td>
                                    <td><a class="btn secondary" href="<?= site_url('ore/utente/' . (int) $riga->id) ?>">Apri</a></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5">Nessun dato disponibile.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
