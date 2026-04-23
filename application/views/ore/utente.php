<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ore utente</title>
    <?php $this->load->view('partials/ui'); ?>
</head>
<body>
    <?php $this->load->view('partials/navigation'); ?>

    <div class="app-wrap">
        <div class="app-card">
            <div class="page-head">
                <div>
                    <h1 class="page-title"><?= html_escape(trim($utente->nome . ' ' . $utente->cognome)) ?></h1>
                    <p class="page-subtitle"><?= html_escape($utente->email) ?></p>
                </div>
                <div class="actions-inline">
                    <a class="btn secondary" href="<?= site_url('admin/utenti') ?>">Lista utenti</a>
                    <a class="btn secondary" href="<?= site_url('report/utenti') ?>">Report utenti</a>
                </div>
            </div>

            <div class="notice">
                <?= ! empty($commessa_filtrata) ? 'Stai guardando il periodo selezionato sulla commessa: ' . html_escape(($commessa_filtrata->codice ? $commessa_filtrata->codice . ' - ' : '') . $commessa_filtrata->attivita) : 'Periodo predefinito: ultimi 30 giorni. Puoi cambiare intervallo o restringere il dettaglio a una commessa.' ?>
            </div>

            <?php
            $query_base = array();
            if (! empty($filtri['dal']))
            {
                $query_base['dal'] = $filtri['dal'];
            }
            if (! empty($filtri['al']))
            {
                $query_base['al'] = $filtri['al'];
            }
            if (! empty($commessa_id))
            {
                $query_base['commessa_id'] = $commessa_id;
            }
            if (! empty($nav_active))
            {
                $query_base['nav'] = $nav_active;
            }
            ?>

            <form method="get" action="<?= site_url('ore/utente/' . (int) $utente->id) ?>" class="form-grid" style="margin-bottom:18px;">
                <?php if (! empty($nav_active)): ?>
                    <input type="hidden" name="nav" value="<?= html_escape($nav_active) ?>">
                <?php endif; ?>
                <div class="summary-grid" style="margin:0;">
                    <div class="field">
                        <label>Dal</label>
                        <input type="date" name="dal" value="<?= html_escape($filtri['dal'] ?? '') ?>">
                    </div>
                    <div class="field">
                        <label>Al</label>
                        <input type="date" name="al" value="<?= html_escape($filtri['al'] ?? '') ?>">
                    </div>
                    <div class="field">
                        <label>Commessa</label>
                        <select name="commessa_id">
                            <option value="">Tutte le commesse</option>
                            <?php foreach ($commesse as $commessa): ?>
                                <option value="<?= (int) $commessa->id ?>" <?= ! empty($commessa_id) && (int) $commessa_id === (int) $commessa->id ? 'selected' : '' ?>>
                                    <?= html_escape(($commessa->codice ? $commessa->codice . ' - ' : '') . $commessa->attivita) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="actions-inline">
                    <button class="btn primary" type="submit">Applica filtro</button>
                    <a class="btn secondary" href="<?= site_url('ore/utente/' . (int) $utente->id) . '?' . http_build_query(array_filter(array_merge(array('nav' => $nav_active ?: 'report_utenti', 'dal' => date('Y-m-d'), 'al' => date('Y-m-d')), ! empty($commessa_id) ? array('commessa_id' => $commessa_id) : array()))) ?>">Oggi</a>
                    <a class="btn secondary" href="<?= site_url('ore/utente/' . (int) $utente->id) . '?' . http_build_query(array_filter(array_merge(array('nav' => $nav_active ?: 'report_utenti', 'dal' => date('Y-m-d', strtotime('-30 days')), 'al' => date('Y-m-d')), ! empty($commessa_id) ? array('commessa_id' => $commessa_id) : array()))) ?>">Ultimi 30 giorni</a>
                </div>
            </form>

            <!-- Scheda di sintesi utile per admin e superadmin. -->
            <div class="summary-grid">
                <div class="summary-card">
                    <div class="label">Totale periodo</div>
                    <div class="value"><?= number_format((float) $totale_ore, 2, ',', '.') ?></div>
                </div>
                <div class="summary-card">
                    <div class="label">Mese corrente</div>
                    <div class="value"><?= number_format((float) $totale_ore_mese, 2, ',', '.') ?></div>
                </div>
                <div class="summary-card">
                    <div class="label">Commesse toccate</div>
                    <div class="value"><?= is_array($riepilogo_commesse) ? count($riepilogo_commesse) : 0 ?></div>
                </div>
                <div class="summary-card">
                    <div class="label">Registrazioni</div>
                    <div class="value"><?= is_array($ore) ? count($ore) : 0 ?></div>
                </div>
            </div>

            <!-- Riepilogo per commessa dell'utente selezionato. -->
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
                                <tr><td colspan="4">Nessuna commessa registrata.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Dettaglio completo delle registrazioni dell'utente. -->
            <div class="section">
                <h2>Dettaglio ore</h2>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Ore</th>
                                <th>Commessa</th>
                                <th>Attività</th>
                                <th>Cliente</th>
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
                                        <td><?= html_escape($riga->cliente_ragione_sociale) ?></td>
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
                                <tr><td colspan="7">Nessuna ora registrata per questo utente.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
