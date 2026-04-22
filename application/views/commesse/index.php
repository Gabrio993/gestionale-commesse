<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commesse</title>
    <?php $this->load->view('partials/ui'); ?>
</head>
<body>
    <?php $this->load->view('partials/navigation'); ?>

    <div class="app-wrap">
        <div class="app-card">
            <div class="page-head">
                <div>
                    <h1 class="page-title">Commesse</h1>
                    <p class="page-subtitle">Elenco delle commesse attive con cliente, codice e attività.</p>
                </div>
                <div class="actions-inline">
                    <?php if (in_array($this->session->userdata('utente_ruolo'), array('admin', 'superadmin'), true)): ?>
                        <a class="btn primary" href="<?= site_url('commesse/nuova') ?>">Nuova commessa</a>
                        <a class="btn secondary" href="<?= site_url('admin') ?>">Dashboard</a>
                    <?php else: ?>
                        <a class="btn secondary" href="<?= site_url('dashboard') ?>">Dashboard</a>
                    <?php endif; ?>
                    <a class="btn secondary" href="<?= site_url('ore/mie') ?>">Le mie ore</a>
                </div>
            </div>

            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Codice</th>
                            <th>Attività</th>
                            <th>Cliente</th>
                            <th>Stato</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (! empty($commesse)): ?>
                            <?php foreach ($commesse as $commessa): ?>
                                <tr>
                                    <td><?= html_escape($commessa->codice) ?></td>
                                    <td><?= html_escape($commessa->attivita) ?></td>
                                    <td><?= html_escape($commessa->cliente_ragione_sociale) ?></td>
                                    <td><span class="badge"><?= html_escape($commessa->stato) ?></span></td>
                                    <td><a class="btn secondary" href="<?= site_url('commesse/dettaglio/' . (int) $commessa->id) ?>">Apri</a></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5">Nessuna commessa presente.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
