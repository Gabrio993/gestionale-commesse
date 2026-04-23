<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuove ore</title>
    <?php $this->load->view('partials/ui'); ?>
</head>
<body>
    <?php $this->load->view('partials/navigation'); ?>

    <div class="app-wrap">
        <div class="app-card" style="max-width: 860px; margin: 0 auto;">
                <div class="page-head">
                    <div>
                        <h1 class="page-title">Inserisci ore</h1>
                    <p class="page-subtitle"><?= html_escape(($commessa->codice ? $commessa->codice . ' - ' : '') . $commessa->attivita) ?></p>
                    </div>
                <a class="btn secondary" href="<?= site_url('commesse/dettaglio/' . (int) $commessa->id) ?>">Torna alla commessa</a>
                </div>

            <!-- Inserimento veloce: si registra una sola riga per giorno e commessa. -->
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
                        <textarea name="descrizione"></textarea>
                    </div>
                    <div class="actions-inline">
                        <button class="btn primary" type="submit">Salva ore</button>
                        <a class="btn secondary" href="<?= site_url('commesse/dettaglio/' . (int) $commessa->id) ?>">Annulla</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
