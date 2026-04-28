<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ruoli utenti</title>
    <?php $this->load->view('partials/ui'); ?>
</head>
<body>
    <?php $this->load->view('partials/navigation'); ?>

    <div class="app-wrap">
        <div class="app-card">
            <div class="page-head">
                <div>
                    <h1 class="page-title">Gestione ruoli</h1>
                    <p class="page-subtitle">Solo il superadmin può cambiare il ruolo di un utente.</p>
                </div>
                <div class="actions-inline">
                    <a class="btn secondary" href="<?= site_url('superadmin') ?>">Dashboard superadmin</a>
                    <a class="btn secondary" href="<?= site_url('report/utenti') ?>">Report utenti</a>
                </div>
            </div>

            <?php if ($this->session->flashdata('notice_success')): ?>
                <div class="notice success"><?= html_escape($this->session->flashdata('notice_success')) ?></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('notice_error')): ?>
                <div class="notice error"><?= html_escape($this->session->flashdata('notice_error')) ?></div>
            <?php endif; ?>

            <!-- Lista utenti con select ruolo: il superadmin può cambiare solo da qui. -->
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Ruolo attuale</th>
                            <th>Cambia ruolo</th>
                            <th>Ore</th>
                            <th>Commesse</th>
                            <th>Reset password</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (! empty($utenti)): ?>
                            <?php foreach ($utenti as $utente): ?>
                                <tr>
                                    <td><?= (int) $utente->id ?></td>
                                    <td><?= html_escape(trim($utente->nome . ' ' . $utente->cognome)) ?></td>
                                    <td><?= html_escape($utente->email) ?></td>
                                    <td><span class="badge"><?= html_escape($utente->ruolo) ?></span></td>
                                    <td>
                                        <form method="post" action="<?= site_url('superadmin/cambia-ruolo') ?>" class="actions-inline">
                                            <input type="hidden" name="utente_id" value="<?= (int) $utente->id ?>">
                                            <select name="ruolo">
                                                <option value="utente" <?= $utente->ruolo === 'utente' ? 'selected' : '' ?>>utente</option>
                                                <option value="admin" <?= $utente->ruolo === 'admin' ? 'selected' : '' ?>>admin</option>
                                                <option value="superadmin" <?= $utente->ruolo === 'superadmin' ? 'selected' : '' ?>>superadmin</option>
                                            </select>
                                            <button class="btn primary" type="submit">Salva</button>
                                        </form>
                                    </td>
                                    <td><a class="btn secondary" href="<?= site_url('ore/utente/' . (int) $utente->id) . '?' . http_build_query(array('nav' => 'ruoli')) ?>">Apri</a></td>
                                    <td><a class="btn secondary" href="<?= site_url('admin/assegna-commesse/' . (int) $utente->id) ?>">Assegna</a></td>
                                    <td>
                                        <form method="post" class="actions-inline" action="<?= site_url('superadmin/reset-password/' . (int) $utente->id) ?>" onsubmit="return confirm('Vuoi reimpostare la password di questo utente?');">
                                            <button class="btn danger" type="submit">Reset</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="8">Nessun utente trovato.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
