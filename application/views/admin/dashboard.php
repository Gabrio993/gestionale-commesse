<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <?php $this->load->view('partials/ui'); ?>
</head>
<body>
    <?php $this->load->view('partials/navigation'); ?>

    <div class="app-wrap">
        <div class="app-card">
            <?php if ($this->session->flashdata('notice_success')): ?>
                <div class="notice success"><?= html_escape($this->session->flashdata('notice_success')) ?></div>
            <?php endif; ?>
            <div class="page-head">
                <div>
                    <h1 class="page-title">Area admin</h1>
                    <p class="page-subtitle">Gestione clienti, commesse, utenti e report operativi.</p>
                </div>
                <span class="badge"><?= html_escape($email_utente ?? '') ?></span>
            </div>

            <!-- Collegamenti rapidi alle aree operative dell'admin. -->
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
                    <div class="label">Utenti</div>
                    <div class="value">Registro</div>
                    <div class="actions-inline">
                        <a class="btn primary" href="<?= site_url('admin/utenti') ?>">Gestione utenti</a>
                    </div>
                </div>
                <div class="summary-card">
                    <div class="label">Report</div>
                    <div class="value">Riepiloghi</div>
                    <div class="actions-inline">
                        <a class="btn primary" href="<?= site_url('report') ?>">Apri report</a>
                        <a class="btn secondary" href="<?= site_url('report/utenti') ?>">Per utenti</a>
                        <a class="btn secondary" href="<?= site_url('report/commesse') ?>">Per commesse</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
