<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le mie ore</title>
    <?php $this->load->view('partials/ui'); ?>
</head>
<body>
    <?php $this->load->view('partials/navigation'); ?>

    <div class="app-wrap">
        <div class="app-card">
            <div class="page-head">
                <div>
                    <h1 class="page-title">Le mie ore</h1>
                    <p class="page-subtitle">Vista personale, con filtro di periodo e riepilogo per commessa.</p>
                </div>
                <div class="actions-inline">
                    <a class="btn secondary" href="<?= site_url('commesse') ?>">Vai alle commesse</a>
                    <a class="btn secondary" href="<?= site_url('report') ?>">Report</a>
                </div>
            </div>

            <div class="notice">
                Periodo predefinito: oggi. Puoi cambiare intervallo, tornare a oggi o aprire gli ultimi 30 giorni.
            </div>

            <?php
            $query_export = array();
            if (! empty($filtri['dal']))
            {
                $query_export['dal'] = $filtri['dal'];
            }
            if (! empty($filtri['al']))
            {
                $query_export['al'] = $filtri['al'];
            }
            $url_export_excel = site_url('ore/mie/export-excel');
            if (! empty($query_export))
            {
                $url_export_excel .= '?' . http_build_query($query_export);
            }
            ?>

            <form method="get" action="<?= site_url('ore/mie') ?>" class="form-grid" style="margin-bottom:18px;">
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
                    <button class="btn primary" type="submit">Applica filtro</button>
                    <a class="btn secondary" href="<?= site_url('ore/mie') ?>">Oggi</a>
                    <a class="btn secondary" href="<?= site_url('ore/mie?dal=' . date('Y-m-d', strtotime('-30 days')) . '&al=' . date('Y-m-d')) ?>">Ultimi 30 giorni</a>
                    <a class="btn secondary" href="<?= $url_export_excel ?>">Esporta Excel</a>
                </div>
            </form>

            <!-- Card di sintesi: aiutano a leggere subito il volume ore del periodo. -->
            <div class="summary-grid">
                <div class="summary-card">
                    <div class="label">Totale nel periodo</div>
                    <div class="value"><?= number_format((float) $totale_ore, 2, ',', '.') ?></div>
                </div>
                <div class="summary-card">
                    <div class="label">Dal</div>
                    <div class="value"><?= html_escape($filtri['dal'] ?? 'n.d.') ?></div>
                </div>
                <div class="summary-card">
                    <div class="label">Al</div>
                    <div class="value"><?= html_escape($filtri['al'] ?? 'n.d.') ?></div>
                </div>
                <div class="summary-card">
                    <div class="label">Commesse toccate</div>
                    <div class="value"><?= is_array($riepilogo_commesse) ? count($riepilogo_commesse) : 0 ?></div>
                </div>
            </div>

            <!-- Riepilogo aggregato per capire come si distribuiscono le ore sulle commesse. -->
            <div class="section">
                <h2>Riepilogo per commessa</h2>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Commessa</th>
                            <th>Attività</th>
                            <th>Cliente</th>
                            <th>Ore</th>
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
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                                <tr><td colspan="4">Nessuna commessa registrata nel periodo selezionato.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Lista dettagliata: qui si controllano, modificano o eliminano le singole righe. -->
            <div class="section">
                <h2>Ultime registrazioni</h2>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Ore</th>
                                <th>Commessa</th>
                                <th>Attività</th>
                                <th>Note</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (! empty($ore)): ?>
                                <?php foreach ($ore as $riga): ?>
                                    <tr>
                                        <td><?= html_escape($riga->data_lavoro) ?></td>
                                        <td><strong><?= html_escape($riga->ore) ?></strong></td>
                                        <td><?= html_escape($riga->commessa_codice) ?></td>
                                        <td><?= html_escape($riga->commessa_attivita) ?></td>
                                        <td><?= html_escape($riga->descrizione) ?></td>
                                        <td>
                                            <div class="actions-inline">
                                                <a class="btn secondary" href="<?= site_url('ore/modifica/' . (int) $riga->id) ?>">Modifica</a>
                                                <form method="post" action="<?= site_url('ore/elimina/' . (int) $riga->id) ?>" onsubmit="return confirm('Eliminare questa registrazione?');">
                                                    <button class="btn danger" type="submit">Elimina</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="6">Nessuna ora registrata nel periodo selezionato.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
