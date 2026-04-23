<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionale ore</title>
    <?php $this->load->view('partials/auth_ui'); ?>
</head>
<body class="auth-page auth-landing">
    <!-- Landing iniziale: il visitatore sceglie se accedere o registrarsi. -->
    <div class="auth-corner-brand">
        <img class="auth-corner-logo" src="<?= base_url('assets/images/logo-auth.png') ?>" alt="Bell Production">
    </div>
    <div class="auth-shell">
        <div class="auth-card">
            <div class="auth-brand">
                <img class="auth-logo" src="<?= base_url('assets/images/auth-bg.png') ?>" alt="Logo">
                <div>
                    <strong>Gestionale ore</strong>
                    <span>Accesso interno per commesse e report</span>
                </div>
            </div>

            <h1 class="auth-title">Benvenuto</h1>
            <p class="auth-text">Accedi con le tue credenziali oppure crea un nuovo account interno.</p>

            <div class="auth-actions">
                <a class="auth-button primary" href="<?= site_url('auth/login') ?>">Login</a>
                <a class="auth-button secondary" href="<?= site_url('auth/registrati') ?>">Registrati</a>
            </div>
        </div>
    </div>
</body>
</html>
