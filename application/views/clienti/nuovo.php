<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuovo cliente</title>
    <?php $this->load->view('partials/ui'); ?>
</head>
<body>
    <?php $this->load->view('partials/navigation'); ?>

    <div class="app-wrap">
        <div class="app-card" style="max-width: 760px; margin: 0 auto;">
            <div class="page-head">
                <div>
                    <h1 class="page-title">Nuovo cliente</h1>
                    <p class="page-subtitle">Inserisci i dati anagrafici del cliente.</p>
                </div>
                <a class="btn secondary" href="<?= site_url('clienti') ?>">Torna ai clienti</a>
            </div>

            <?= validation_errors('<div class="notice error">', '</div>') ?>

            <form method="post" action="<?= site_url('clienti/salva') ?>" class="form-grid">
                <div class="field">
                    <label>Ragione sociale</label>
                    <input type="text" name="ragione_sociale" value="<?= html_escape($this->input->post('ragione_sociale', true)) ?>" required>
                </div>
                <div class="field">
                    <label>Partita IVA</label>
                    <input type="text" name="partita_iva" value="<?= html_escape($this->input->post('partita_iva', true)) ?>">
                </div>
                <div class="field">
                    <label>Codice fiscale</label>
                    <input type="text" name="codice_fiscale" value="<?= html_escape($this->input->post('codice_fiscale', true)) ?>">
                </div>
                <div class="field">
                    <label>Indirizzo</label>
                    <input type="text" name="indirizzo" value="<?= html_escape($this->input->post('indirizzo', true)) ?>">
                </div>
                <div class="field">
                    <label>Note</label>
                    <textarea name="note"><?= html_escape($this->input->post('note', true)) ?></textarea>
                </div>
                <div class="actions-inline">
                    <button class="btn primary" type="submit">Salva cliente</button>
                    <a class="btn secondary" href="<?= site_url('clienti') ?>">Annulla</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
