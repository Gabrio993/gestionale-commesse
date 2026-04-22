<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clienti</title>
    <?php $this->load->view('partials/ui'); ?>
</head>
<body>
    <?php $this->load->view('partials/navigation'); ?>

    <div class="app-wrap">
        <div class="app-card">
            <div class="page-head">
                <div>
                    <h1 class="page-title">Clienti</h1>
                    <p class="page-subtitle">Anagrafica clienti collegata alle commesse.</p>
                </div>
                <div class="actions-inline">
                    <a class="btn primary" href="<?= site_url('clienti/nuovo') ?>">Nuovo cliente</a>
                    <a class="btn secondary" href="<?= site_url('admin') ?>">Dashboard</a>
                </div>
            </div>

            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ragione sociale</th>
                            <th>Partita IVA</th>
                            <th>Codice fiscale</th>
                            <th>Attivo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (! empty($clienti)): ?>
                            <?php foreach ($clienti as $cliente): ?>
                                <tr>
                                    <td><?= (int) $cliente->id ?></td>
                                    <td><?= html_escape($cliente->ragione_sociale) ?></td>
                                    <td><?= html_escape($cliente->partita_iva) ?></td>
                                    <td><?= html_escape($cliente->codice_fiscale) ?></td>
                                    <td><?= (int) $cliente->attivo ? '<span class="badge success">Sì</span>' : '<span class="badge danger">No</span>' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5">Nessun cliente presente.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
