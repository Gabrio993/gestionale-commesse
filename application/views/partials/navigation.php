<?php
$ruolo = $this->session->userdata('utente_ruolo');
$nome = $this->session->userdata('utente_nome');
$email = $this->session->userdata('utente_email');

// La navbar usa controller e metodo corrente per evidenziare un solo bottone alla volta.
$current_class = strtolower($this->router->fetch_class());
$current_method = strtolower($this->router->fetch_method());

$active_item = 'dashboard';
$has_forced_nav = false;
if (! empty($nav_active) && is_string($nav_active)) {
    $allowed_nav_items = array(
        'dashboard',
        'commesse',
        'ore',
        'report',
        'report_utenti',
        'report_commesse',
        'clienti',
        'utenti',
        'ruoli',
    );
    if (in_array($nav_active, $allowed_nav_items, true)) {
        $active_item = $nav_active;
        $has_forced_nav = true;
    }
}

if (! $has_forced_nav) {
    if ($current_class === 'commesse') {
        $active_item = 'commesse';
    } elseif ($current_class === 'ore') {
        $active_item = 'ore';
    } elseif ($current_class === 'reporti') {
        if ($current_method === 'utenti') {
            $active_item = 'report_utenti';
        } elseif ($current_method === 'commesse') {
            $active_item = 'report_commesse';
        } else {
            $active_item = 'report';
        }
    } elseif ($current_class === 'clienti') {
        $active_item = 'clienti';
    } elseif ($current_class === 'admin') {
        $active_item = ($current_method === 'utenti') ? 'utenti' : 'dashboard';
    } elseif ($current_class === 'superadmin') {
        $active_item = ($current_method === 'utenti') ? 'ruoli' : 'dashboard';
    }
}

$nav_class = function ($item) use ($active_item) {
    return $active_item === $item ? 'primary' : 'secondary';
};
?>
<div class="app-topbar">
    <div class="app-topbar-inner">
        <div class="brand">
            <strong>Gestionale ore</strong>
            <span>Commessa, ore, report e accessi</span>
        </div>

        <div class="nav-links">
            <a class="<?= $nav_class('dashboard') ?>" href="<?= site_url('dashboard') ?>">Dashboard</a>
            <a class="<?= $nav_class('commesse') ?>" href="<?= site_url('commesse') ?>">Commesse</a>
            <a class="<?= $nav_class('ore') ?>" href="<?= site_url('ore/mie') ?>">Le mie ore</a>
            <a class="<?= $nav_class('report') ?>" href="<?= site_url('reporti') ?>">Report</a>

            <?php if (in_array($ruolo, array('admin', 'superadmin'), true)): ?>
                <a class="<?= $nav_class('clienti') ?>" href="<?= site_url('clienti') ?>">Clienti</a>
                <a class="<?= $nav_class('utenti') ?>" href="<?= site_url('admin/utenti') ?>">Utenti</a>
                <a class="<?= $nav_class('report_utenti') ?>" href="<?= site_url('reporti/utenti') ?>">Report utenti</a>
                <a class="<?= $nav_class('report_commesse') ?>" href="<?= site_url('reporti/commesse') ?>">Report commesse</a>
            <?php endif; ?>

            <?php if ($ruolo === 'superadmin'): ?>
                <a class="<?= $nav_class('ruoli') ?>" href="<?= site_url('superadmin/utenti') ?>">Ruoli</a>
            <?php endif; ?>

            <a class="secondary" href="<?= site_url('auth/logout') ?>">Logout</a>
        </div>

        <div class="user-chip">
            <div class="avatar"><?= strtoupper(substr((string) $nome, 0, 1)) ?></div>
            <div>
                <div style="font-weight:700; line-height:1.2;"><?= html_escape($nome) ?></div>
                <div style="color: var(--muted); font-size: 13px; line-height:1.2;"><?= html_escape($email) ?> · <?= html_escape($ruolo) ?></div>
            </div>
        </div>
    </div>
</div>
