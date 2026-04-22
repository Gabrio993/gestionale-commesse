<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica ore</title>
    <?php $this->load->view('partials/ui'); ?>
</head>
<body>
    <?php $this->load->view('partials/navigation'); ?>

    <div class="app-wrap">
        <div class="app-card" style="max-width: 860px; margin: 0 auto;">
                <div class="page-head">
                    <div>
                        <h1 class="page-title">Modifica registrazione</h1>
                    <p class="page-subtitle"><?= html_escape(($commessa->codice ? $commessa->codice . ' - ' : '') . $commessa->attivita) ?></p>
                    </div>
                <a class="btn secondary" href="<?= site_url('ore/mie') ?>">Torna alle mie ore</a>
                </div>

            <?= validation_errors('<div class="notice error">', '</div>') ?>

            <form method="post" action="<?= site_url('ore/aggiorna/' . (int) $registrazione->id) ?>" class="form-grid">
                <div class="summary-card">
                    <div class="label">Commessa bloccata</div>
                    <div class="value"><?= html_escape(($commessa->codice ? $commessa->codice . ' - ' : '') . $commessa->attivita) ?></div>
                    <p class="page-subtitle"><?= html_escape($commessa->cliente_ragione_sociale) ?></p>
                </div>

                <div class="field">
                    <label>Data lavoro</label>
                    <input type="date" name="data_lavoro" value="<?= html_escape(set_value('data_lavoro', $registrazione->data_lavoro)) ?>" required>
                </div>
                <div class="field">
                    <label>Ore</label>
                    <input type="number" name="ore" min="0" step="0.25" value="<?= html_escape(set_value('ore', $registrazione->ore)) ?>" required>
                </div>
                <div class="field">
                    <label>Note</label>
                    <textarea name="descrizione"><?= html_escape(set_value('descrizione', $registrazione->descrizione)) ?></textarea>
                </div>
                <div class="actions-inline">
                    <button class="btn primary" type="submit">Aggiorna</button>
                    <a class="btn secondary" href="<?= site_url('ore/mie') ?>">Annulla</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
