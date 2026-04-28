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
                    <p class="page-subtitle">Confronto ore tra utenti registrati, con filtro di periodo e facoltativo per commessa.</p>
                </div>
                <div class="actions-inline">
                    <a class="btn secondary" href="<?= site_url('report') ?>">Report generale</a>
                    <a class="btn secondary" href="<?= site_url('report/commesse') ?>">Report commesse</a>
                </div>
            </div>

            <div class="notice">
                Periodo predefinito: ultimi 30 giorni. Puoi restringere il report con calendario e commessa.
            </div>

            <?php
            $query_base = array();
    if (! empty($filtri['dal'])) {
        $query_base['dal'] = $filtri['dal'];
    }
    if (! empty($filtri['al'])) {
        $query_base['al'] = $filtri['al'];
    }
    if (! empty($filtri['commessa_id'])) {
        $query_base['commessa_id'] = $filtri['commessa_id'];
    }
    ?>

            <form method="get" action="<?= site_url('report/utenti') ?>" class="form-grid" style="margin-bottom:18px;">
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
                                <option value="<?= (int) $commessa->id ?>" <?= ! empty($filtri['commessa_id']) && (int) $filtri['commessa_id'] === (int) $commessa->id ? 'selected' : '' ?>>
                                    <?= html_escape(($commessa->codice ? $commessa->codice . ' - ' : '') . $commessa->attivita) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="actions-inline">
                    <button class="btn primary" type="submit">Applica</button>
                    <a class="btn secondary" href="<?= site_url('report/utenti?' . http_build_query(array_filter(array('dal' => date('Y-m-d'), 'al' => date('Y-m-d'), 'commessa_id' => $filtri['commessa_id'] ?? null)))) ?>">Oggi</a>
                    <a class="btn secondary" href="<?= site_url('report/utenti?' . http_build_query(array_filter(array('dal' => date('Y-m-d', strtotime('-30 days')), 'al' => date('Y-m-d'), 'commessa_id' => $filtri['commessa_id'] ?? null)))) ?>">Ultimi 30 giorni</a>
                </div>
            </form>

            <div class="summary-grid">
                <div class="summary-card">
                    <div class="label">Totale ore filtrate</div>
                    <div class="value"><?= number_format((float) $totale_ore, 2, ',', '.') ?></div>
                </div>
                <div class="summary-card">
                    <div class="label">Registrazioni filtrate</div>
                    <div class="value"><?= (int) ($totale_registrazioni ?? 0) ?></div>
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

            <!-- Tabella sintetica per confrontare rapidamente le ore tra utenti. -->
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
                                        <td>
                                            <a class="btn secondary" href="<?= site_url('ore/utente/' . (int) $riga->id) . '?' . http_build_query(array_merge($query_base, array('nav' => 'report_utenti'))) ?>">Apri</a>
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
