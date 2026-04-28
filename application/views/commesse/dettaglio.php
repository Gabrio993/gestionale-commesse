<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dettaglio commessa</title>
    <?php $this->load->view('partials/ui'); ?>
</head>
<body>
    <?php $this->load->view('partials/navigation'); ?>

    <div class="app-wrap">
        <div class="app-card">
            <div class="page-head">
                <div>
                    <h1 class="page-title">
                        <?php if (! empty($commessa->codice)): ?>
                            <?= html_escape($commessa->codice) ?> - <?= html_escape($commessa->attivita) ?>
                        <?php else: ?>
                            <?= html_escape($commessa->attivita) ?>
                        <?php endif; ?>
                    </h1>
                    <p class="page-subtitle">Cliente: <?= html_escape($commessa->cliente_ragione_sociale) ?></p>
                </div>
                <div class="actions-inline">
                    <a class="btn secondary" href="<?= site_url('commesse') ?>">Tutte le commesse</a>
                    <a class="btn primary" href="<?= site_url('ore/nuova/' . (int) $commessa->id) ?>">Inserisci ore</a>
                </div>
            </div>

            <div class="notice">
                <?= ! empty($filtri['dal']) || ! empty($filtri['al']) ? 'Hai applicato un filtro di periodo al dettaglio della commessa.' : 'Periodo predefinito: ultimi 30 giorni. Puoi filtrare lo storico con calendario.' ?>
            </div>

            <form method="get" action="<?= site_url('commesse/dettaglio/' . (int) $commessa->id) ?>" class="form-grid" style="margin-bottom:18px;">
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
                </div>
                <div class="actions-inline">
                    <button class="btn primary" type="submit">Applica</button>
                    <a class="btn secondary" href="<?= site_url('commesse/dettaglio/' . (int) $commessa->id) . '?' . http_build_query(array_filter(array('nav' => $nav_active ?: 'report_commesse', 'dal' => date('Y-m-d'), 'al' => date('Y-m-d')))) ?>">Oggi</a>
                    <a class="btn secondary" href="<?= site_url('commesse/dettaglio/' . (int) $commessa->id) . '?' . http_build_query(array_filter(array('nav' => $nav_active ?: 'report_commesse', 'dal' => date('Y-m-d', strtotime('-30 days')), 'al' => date('Y-m-d')))) ?>">Ultimi 30 giorni</a>
                </div>
            </form>

            <!-- Riepilogo rapido della commessa prima del form di inserimento ore. -->
            <div class="summary-grid">
                <div class="summary-card">
                    <div class="label">Stato</div>
                    <div class="value"><span class="badge"><?= html_escape($commessa->stato) ?></span></div>
                </div>
                <div class="summary-card">
                    <div class="label">Totale nel periodo</div>
                    <div class="value"><?= number_format((float) $totale_ore, 2, ',', '.') ?></div>
                </div>
                <div class="summary-card">
                    <div class="label">Registrazioni</div>
                    <div class="value"><?= (int) $totale_registrazioni ?></div>
                </div>
                <div class="summary-card">
                    <div class="label">Periodo</div>
                    <div class="value"><?= html_escape(($filtri['dal'] ?? 'n.d.') . ' / ' . ($filtri['al'] ?? 'n.d.')) ?></div>
                </div>
            </div>

            <div class="section">
                <h2>Descrizione</h2>
                <div class="notice"><?= html_escape($commessa->descrizione ?: 'Nessuna descrizione inserita.') ?></div>
            </div>

            <!-- Form rapido: inserimento ore direttamente dalla scheda commessa. -->
            <div class="section">
                <h2>Inserisci ore su questa commessa</h2>
                <div class="app-card" style="box-shadow:none; background: var(--surface-soft); border-style:dashed;">
                    <form method="post" action="<?= site_url('ore/salva') ?>" class="form-grid">
                        <input type="hidden" name="commessa_id" value="<?= (int) $commessa->id ?>">
                        <div class="field">
                            <label>Data lavoro</label>
                            <input type="date" name="data_lavoro" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="field">
                            <label>Ore</label>
                            <input type="number" name="ore" min="0" step="0.25" required>
                        </div>
                        <div class="field">
                            <label>Note</label>
                            <textarea name="descrizione" placeholder="Attività svolta..."></textarea>
                        </div>
                        <div class="actions-inline">
                            <button class="btn primary" type="submit">Salva ore</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Storico delle registrazioni legate a questa commessa. -->
            <div class="section">
                <h2>Registrazioni su questa commessa</h2>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Ore</th>
                                <th>Utente</th>
                                <th>Note</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (! empty($ore_commessa)): ?>
                                <?php foreach ($ore_commessa as $riga): ?>
                                    <tr>
                                        <td><?= html_escape($riga->data_lavoro) ?></td>
                                        <td><strong><?= html_escape($riga->ore) ?></strong></td>
                                        <td><?= html_escape(trim($riga->nome . ' ' . $riga->cognome)) ?></td>
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
                                <tr><td colspan="5">Nessuna registrazione presente.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
