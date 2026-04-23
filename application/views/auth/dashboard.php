<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <?php $this->load->view('partials/ui'); ?>
</head>
<body>
    <?php $this->load->view('partials/navigation'); ?>

    <div class="app-wrap">
        <div class="app-card">
            <div class="page-head">
                <div>
                    <h1 class="page-title">Benvenuto, <?= html_escape($nome_utente ?? '') ?></h1>
                    <p class="page-subtitle">Da qui puoi vedere le tue commesse, le ore inserite e i report base.</p>
                </div>
                <span class="badge success"><?= html_escape($ruolo_utente ?? 'utente') ?></span>
            </div>

            <!-- Tre accessi rapidi: commesse, ore personali e report. -->
            <div class="summary-grid">
                <div class="summary-card">
                    <div class="label">Commessa</div>
                    <div class="value">Ore</div>
                    <p class="page-subtitle">Vai all'elenco delle commesse attive.</p>
                    <div class="actions-inline">
                        <a class="btn primary" href="<?= site_url('commesse') ?>">Apri commesse</a>
                    </div>
                </div>
                <div class="summary-card">
                    <div class="label">Le mie ore</div>
                    <div class="value">Storico</div>
                    <p class="page-subtitle">Controlla e correggi le tue registrazioni.</p>
                    <div class="actions-inline">
                        <a class="btn primary" href="<?= site_url('ore/mie') ?>">Apri ore</a>
                    </div>
                </div>
                <div class="summary-card">
                    <div class="label">Report</div>
                    <div class="value">Base</div>
                    <p class="page-subtitle">Riepilogo ore, commesse e ultimi inserimenti.</p>
                    <div class="actions-inline">
                        <a class="btn primary" href="<?= site_url('reporti') ?>">Apri report</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
