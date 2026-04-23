<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assegna commesse</title>
    <?php $this->load->view('partials/ui'); ?>
</head>
<body>
    <?php $this->load->view('partials/navigation'); ?>

    <div class="app-wrap">
        <div class="app-card">
            <div class="page-head">
                <div>
                    <h1 class="page-title">Assegna commesse</h1>
                    <p class="page-subtitle"><?= html_escape(trim($utente->nome . ' ' . $utente->cognome)) ?> - <?= html_escape($utente->email) ?></p>
                </div>
                <div class="actions-inline">
                    <a class="btn secondary" href="<?= site_url('admin/utenti') ?>">Lista utenti</a>
                    <a class="btn secondary" href="<?= site_url('ore/utente/' . (int) $utente->id) ?>">Ore utente</a>
                </div>
            </div>

            <div class="notice">
                Seleziona una o più commesse. L'utente potrà inserire ore solo su quelle assegnate.
            </div>

            <form method="post" action="<?= site_url('admin/salva-assegnazioni/' . (int) $utente->id) ?>">
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Assegna</th>
                                <th>Codice</th>
                                <th>Attività</th>
                                <th>Cliente</th>
                                <th>Stato</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (! empty($commesse)): ?>
                                <?php foreach ($commesse as $commessa): ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="commesse[]" value="<?= (int) $commessa->id ?>" <?= in_array((int) $commessa->id, $assegnate_ids, true) ? 'checked' : '' ?>>
                                        </td>
                                        <td><?= html_escape($commessa->codice) ?></td>
                                        <td><?= html_escape($commessa->attivita) ?></td>
                                        <td><?= html_escape($commessa->cliente_ragione_sociale) ?></td>
                                        <td><span class="badge"><?= html_escape($commessa->stato) ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5">Nessuna commessa disponibile.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="actions-inline" style="margin-top:18px;">
                    <button class="btn primary" type="submit">Salva assegnazioni</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
