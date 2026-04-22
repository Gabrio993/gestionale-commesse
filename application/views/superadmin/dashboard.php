<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superadmin</title>
    <?php $this->load->view('partials/ui'); ?>
</head>
<body>
    <?php $this->load->view('partials/navigation'); ?>

    <div class="app-wrap">
        <div class="app-card">
            <div class="page-head">
                <div>
                    <h1 class="page-title">Area superadmin</h1>
                    <p class="page-subtitle">Stessa gestione dell'admin, con in più il controllo dei ruoli utenti.</p>
                </div>
                <span class="badge success"><?= html_escape($email_utente ?? '') ?></span>
            </div>

            <div class="summary-grid">
                <div class="summary-card">
                    <div class="label">Clienti</div>
                    <div class="value">Anagrafica</div>
                    <div class="actions-inline">
                        <a class="btn primary" href="<?= site_url('clienti') ?>">Apri clienti</a>
                        <a class="btn secondary" href="<?= site_url('clienti/nuovo') ?>">Nuovo cliente</a>
                    </div>
                </div>
                <div class="summary-card">
                    <div class="label">Commesse</div>
                    <div class="value">Lavoro</div>
                    <div class="actions-inline">
                        <a class="btn primary" href="<?= site_url('commesse') ?>">Apri commesse</a>
                        <a class="btn secondary" href="<?= site_url('commesse/nuova') ?>">Nuova commessa</a>
                    </div>
                </div>
                <div class="summary-card">
                    <div class="label">Ruoli</div>
                    <div class="value">Accessi</div>
                    <div class="actions-inline">
                        <a class="btn primary" href="<?= site_url('superadmin/utenti') ?>">Gestione ruoli</a>
                    </div>
                </div>
                <div class="summary-card">
                    <div class="label">Report</div>
                    <div class="value">Riepiloghi</div>
                    <div class="actions-inline">
                        <a class="btn primary" href="<?= site_url('reporti') ?>">Apri report</a>
                        <a class="btn secondary" href="<?= site_url('reporti/utenti') ?>">Per utenti</a>
                        <a class="btn secondary" href="<?= site_url('reporti/commesse') ?>">Per commesse</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
