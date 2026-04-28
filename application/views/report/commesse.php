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
                    <p class="page-subtitle">Ore aggregate per commessa e cliente, con filtro di periodo.</p>
                </div>
                <div class="actions-inline">
                    <a class="btn secondary" href="<?= site_url('report') ?>">Report generale</a>
                    <a class="btn secondary" href="<?= site_url('report/utenti') ?>">Report utenti</a>
                </div>
            </div>

            <div class="notice">
                Periodo predefinito: ultimi 30 giorni. Puoi restringere il riepilogo con il calendario.
            </div>

            <form method="get" action="<?= site_url('report/commesse') ?>" class="form-grid" style="margin-bottom:18px;">
                <div class="summary-grid" style="margin:0;">
                    <div class="field">
                        <label>Dal</label>
                        <input type="date" name="dal" value="<?= html_escape($filtri['dal'] ?? '') ?>">
                    </div>
                    <div class="field">
                        <label>Al</label>
                        <input type="date" name="al" value="<?= html_escape($filtri['al'] ?? '') ?>">
                    </div>
                </div>
                <div class="actions-inline">
                    <button class="btn primary" type="submit">Applica</button>
                    <a class="btn secondary" href="<?= site_url('report/commesse?' . http_build_query(array('dal' => date('Y-m-d'), 'al' => date('Y-m-d')))) ?>">Oggi</a>
                    <a class="btn secondary" href="<?= site_url('report/commesse?' . http_build_query(array('dal' => date('Y-m-d', strtotime('-30 days')), 'al' => date('Y-m-d')))) ?>">Ultimi 30 giorni</a>
                </div>
            </form>

            <div class="summary-grid">
                <div class="summary-card">
                    <div class="label">Totale ore filtrate</div>
                    <div class="value"><?= number_format((float) $totale_ore, 2, ',', '.') ?></div>
                </div>
                <div class="summary-card">
                    <div class="label">Commesse presenti</div>
                    <div class="value"><?= (int) count($riepilogo_commesse) ?></div>
                </div>
                <div class="summary-card">
                    <div class="label">Dal</div>
                    <div class="value"><?= html_escape($filtri['dal'] ?? 'n.d.') ?></div>
                </div>
                <div class="summary-card">
                    <div class="label">Al</div>
                    <div class="value"><?= html_escape($filtri['al'] ?? 'n.d.') ?></div>
                </div>
            </div>

            <!-- Tabella aggregata: ogni riga rappresenta una commessa con il totale ore. -->
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
                                    <td>
                                        <a class="btn secondary" href="<?= site_url('commesse/dettaglio/' . (int) $riga->id) . '?' . http_build_query(array('nav' => 'report_commesse', 'dal' => $filtri['dal'] ?? null, 'al' => $filtri['al'] ?? null)) ?>">Apri</a>
                                    </td>
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
