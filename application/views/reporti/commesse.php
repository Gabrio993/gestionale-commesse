<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report commesse</title>
    <?php $this->load->view('partials/ui'); ?>
</head>
<body>
    <?php $this->load->view('partials/navigation'); ?>

    <div class="app-wrap">
        <div class="app-card">
            <div class="page-head">
                <div>
                    <h1 class="page-title">Report commesse</h1>
                    <p class="page-subtitle">Ore aggregate per commessa e cliente.</p>
                </div>
                <div class="actions-inline">
                    <a class="btn secondary" href="<?= site_url('reporti') ?>">Report generale</a>
                </div>
            </div>

            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Commessa</th>
                            <th>Attività</th>
                            <th>Cliente</th>
                            <th>Ore totali</th>
                            <th>Apri</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (! empty($riepilogo_commesse)): ?>
                            <?php foreach ($riepilogo_commesse as $riga): ?>
                                <tr>
                                    <td><?= html_escape($riga->codice) ?></td>
                                    <td><?= html_escape($riga->attivita) ?></td>
                                    <td><?= html_escape($riga->cliente_ragione_sociale) ?></td>
                                    <td><strong><?= number_format((float) $riga->totale_ore, 2, ',', '.') ?></strong></td>
                                    <td><a class="btn secondary" href="<?= site_url('commesse/dettaglio/' . (int) $riga->id) ?>">Apri</a></td>
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
