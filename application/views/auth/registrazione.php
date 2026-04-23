<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione</title>
    <?php $this->load->view('partials/auth_ui'); ?>
</head>
<body class="auth-page">
    <!-- Registrazione pubblica: crea un nuovo utente con ruolo base. -->
    <div class="auth-shell">
        <div class="auth-card">
            <div class="auth-brand">
                <img class="auth-logo" src="<?= base_url('assets/images/auth-bg.png') ?>" alt="Logo">
                <div>
                    <strong>Gestionale ore</strong>
                    <span>Crea un nuovo account interno</span>
                </div>
            </div>

            <h1 class="auth-title">Registrazione</h1>
            <p class="auth-text">Crea il tuo account interno.</p>

            <?php if (isset($errore)): ?>
                <div class="auth-err"><?= html_escape($errore) ?></div>
            <?php endif; ?>

            <?= validation_errors('<div class="auth-err">', '</div>') ?>

            <form method="post" action="<?= site_url('auth/salva_registrazione') ?>" class="auth-grid">
                <div class="auth-field">
                    <label>Nome</label>
                    <input type="text" name="nome" value="<?= set_value('nome') ?>" required>
                </div>

                <div class="auth-field">
                    <label>Cognome</label>
                    <input type="text" name="cognome" value="<?= set_value('cognome') ?>" required>
                </div>

                <div class="auth-field">
                    <label>Email</label>
                    <input type="email" name="email" value="<?= set_value('email') ?>" required>
                </div>

                <div class="auth-field">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>

                <div class="auth-field">
                    <label>Conferma password</label>
                    <input type="password" name="conferma_password" required>
                </div>

                <div class="auth-actions">
                    <button class="auth-button primary" type="submit">Registrati</button>
                    <a class="auth-link secondary" href="<?= site_url('auth/login') ?>">Ho già un account</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
