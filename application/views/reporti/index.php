<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= html_escape($titolo ?? 'Report') ?></title>
    <?php $this->load->view('partials/ui'); ?>
</head>
<body>
    <?php $this->load->view('partials/navigation'); ?>

    <div class="app-wrap">
        <div class="app-card">
            <div class="page-head">
                <div>
                    <h1 class="page-title"><?= html_escape($titolo ?? 'Report') ?></h1>
                    <p class="page-subtitle">Filtra per periodo e, se serve, per commessa. I totali si aggiornano sui filtri attivi.</p>
                </div>
                <?php if ($globale): ?>
                    <div class="actions-inline">
                        <a class="btn secondary" href="<?= site_url('reporti/utenti') ?>">Per utenti</a>
                        <a class="btn secondary" href="<?= site_url('reporti/commesse') ?>">Per commesse</a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="notice">
                Periodo predefinito: ultimi 30 giorni. Se vuoi vedere tutto, lascia vuoti i campi e premi il filtro.
            </div>

            <!-- I filtri sono in GET per permettere di condividere l'URL del report. -->
            <form method="get" action="<?= site_url('reporti') ?>" class="form-grid" style="margin-bottom:18px;">
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
                    <button class="btn primary" type="submit">Applica filtro</button>
                    <a class="btn secondary" href="<?= site_url('reporti') ?>">Ultimi 30 giorni</a>
                </div>
            </form>

            <!-- Card con i totali principali del periodo filtrato. -->
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

            <?php if ($globale): ?>
                <!-- Report sintetico degli utenti: visibile solo ad admin e superadmin. -->
                <div class="section">
                    <h2>Riepilogo per utenti</h2>
                    <div class="table-wrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Utente</th>
                                    <th>Email</th>
                                    <th>Ruolo</th>
                                    <th>Ore</th>
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
            <?php endif; ?>

            <!-- Report per commessa: utile per leggere la distribuzione del lavoro. -->
            <div class="section">
                <h2>Riepilogo per commesse</h2>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Commessa</th>
                                <th>Attività</th>
                                <th>Cliente</th>
                                <th>Ore</th>
                                <?php if ($globale): ?><th>Apri</th><?php endif; ?>
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
                                        <?php if ($globale): ?><td><a class="btn secondary" href="<?= site_url('commesse/dettaglio/' . (int) $riga->id) ?>">Apri</a></td><?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="<?= $globale ? 5 : 4 ?>">Nessun dato disponibile.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Ultime registrazioni: la vista piu utile per controlli rapidi. -->
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
                                <th>Cliente</th>
                                <?php if ($globale): ?><th>Utente</th><?php endif; ?>
                                <th>Note</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (! empty($ultime_registrazioni)): ?>
                                <?php foreach ($ultime_registrazioni as $riga): ?>
                                    <tr>
                                        <td><?= html_escape($riga->data_lavoro) ?></td>
                                        <td><strong><?= html_escape($riga->ore) ?></strong></td>
                                        <td><?= html_escape($riga->commessa_codice) ?></td>
                                        <td><?= html_escape($riga->commessa_attivita) ?></td>
                                        <td><?= html_escape($riga->cliente_ragione_sociale) ?></td>
                                        <?php if ($globale): ?><td><?= html_escape(trim($riga->utente_nome . ' ' . $riga->utente_cognome)) ?></td><?php endif; ?>
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
                                <tr><td colspan="<?= $globale ? 8 : 7 ?>">Nessuna registrazione presente nel periodo selezionato.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
