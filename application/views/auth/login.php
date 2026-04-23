<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <?php $this->load->view('partials/auth_ui'); ?>
</head>
<body class="auth-page">
    <!-- Form di login: dopo l'autenticazione il controller decide la dashboard corretta in base al ruolo. -->
    <div class="auth-shell">
        <div class="auth-card">
            <div class="auth-brand">
                <img class="auth-logo" src="<?= base_url('assets/images/auth-bg.png') ?>" alt="Logo">
                <div>
                    <strong>Gestionale ore</strong>
                    <span>Entra con email e password</span>
                </div>
            </div>

            <h1 class="auth-title">Login</h1>
            <p class="auth-text">Accedi con la tua email e password.</p>

            <?php if (isset($messaggio)): ?>
                <div class="auth-msg"><?= html_escape($messaggio) ?></div>
            <?php endif; ?>

            <?php if (isset($errore)): ?>
                <div class="auth-err"><?= html_escape($errore) ?></div>
            <?php endif; ?>

            <?= validation_errors('<div class="auth-err">', '</div>') ?>

            <form method="post" action="<?= site_url('auth/autentica') ?>" class="auth-grid">
                <div class="auth-field">
                    <label>Email</label>
                    <input type="email" name="email" value="<?= set_value('email') ?>" required>
                </div>

                <div class="auth-field">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>

                <div class="auth-actions">
                    <button class="auth-button primary" type="submit">Accedi</button>
                    <a class="auth-link secondary" href="<?= site_url('auth/registrati') ?>">Registrati</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
