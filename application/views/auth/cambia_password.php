<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambia password</title>
    <?php $this->load->view('partials/auth_ui'); ?>
</head>
<body class="auth-page">
    <!-- Cambio password self-service: l'utente aggiorna la propria password dopo un reset o quando vuole. -->
    <div class="auth-shell">
        <div class="auth-card">
            <div class="auth-brand">
                <img class="auth-logo" src="<?= base_url('assets/images/auth-bg.png') ?>" alt="Logo">
                <div>
                    <strong>Gestionale ore</strong>
                    <span>Cambio password personale</span>
                </div>
            </div>

            <h1 class="auth-title">Cambia password</h1>
            <p class="auth-text">Aggiorna la tua password in autonomia.</p>

            <?php if (isset($errore)): ?>
                <div class="auth-err"><?= html_escape($errore) ?></div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('notice_success')): ?>
                <div class="auth-msg"><?= html_escape($this->session->flashdata('notice_success')) ?></div>
            <?php endif; ?>

            <?= validation_errors('<div class="auth-err">', '</div>') ?>

            <form method="post" action="<?= site_url('auth/salva-password') ?>" class="auth-grid">
                <div class="auth-field">
                    <label>Password attuale</label>
                    <input type="password" name="password_attuale" required>
                </div>

                <div class="auth-field">
                    <label>Nuova password</label>
                    <input type="password" name="password_nuova" required>
                </div>

                <div class="auth-field">
                    <label>Conferma nuova password</label>
                    <input type="password" name="conferma_password_nuova" required>
                </div>

                <div class="auth-actions">
                    <button class="auth-button primary" type="submit">Aggiorna</button>
                    <a class="auth-link secondary" href="<?= site_url('dashboard') ?>">Annulla</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
