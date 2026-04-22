<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuova commessa</title>
    <?php $this->load->view('partials/ui'); ?>
</head>
<body>
    <?php $this->load->view('partials/navigation'); ?>

    <div class="app-wrap">
        <div class="app-card" style="max-width: 820px; margin: 0 auto;">
            <div class="page-head">
                <div>
                    <h1 class="page-title">Nuova commessa</h1>
                    <p class="page-subtitle">Collega la commessa a un cliente già presente.</p>
                </div>
                <a class="btn secondary" href="<?= site_url('commesse') ?>">Torna alle commesse</a>
            </div>

            <?= validation_errors('<div class="notice error">', '</div>') ?>

            <form method="post" action="<?= site_url('commesse/salva') ?>" class="form-grid">
                <div class="field">
                    <label>Cliente</label>
                    <select name="cliente_id" required>
                        <option value="">Seleziona un cliente</option>
                        <?php foreach ($clienti as $cliente): ?>
                            <option value="<?= (int) $cliente->id ?>" <?= $this->input->post('cliente_id', true) == $cliente->id ? 'selected' : '' ?>>
                                <?= html_escape($cliente->ragione_sociale) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field">
                    <label>Codice commessa</label>
                    <input type="text" name="codice" value="<?= html_escape($this->input->post('codice', true)) ?>" placeholder="Es. PRJ-001">
                </div>
                <div class="field">
                    <label>Attività</label>
                    <input type="text" name="attivita" value="<?= html_escape($this->input->post('attivita', true)) ?>" required placeholder="Es. Palinsesti">
                </div>
                <div class="field">
                    <label>Descrizione</label>
                    <textarea name="descrizione"><?= html_escape($this->input->post('descrizione', true)) ?></textarea>
                </div>
                <div class="actions-inline">
                    <button class="btn primary" type="submit">Salva commessa</button>
                    <a class="btn secondary" href="<?= site_url('commesse') ?>">Annulla</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
