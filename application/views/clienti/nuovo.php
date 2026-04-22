<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuovo cliente</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; min-height: 100vh; display: grid; place-items: center; background: #f4f6f8; color: #1f2937; }
        .card { width: min(92vw, 520px); background: #fff; border: 1px solid #e5e7eb; border-radius: 16px; padding: 28px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06); }
        label { display: block; margin: 14px 0 6px; font-weight: 700; }
        input, textarea { width: 100%; box-sizing: border-box; padding: 12px; border: 1px solid #d1d5db; border-radius: 10px; }
        textarea { min-height: 110px; }
        button, .link { display: inline-block; margin-top: 16px; padding: 12px 16px; border-radius: 10px; border: 0; text-decoration: none; font-weight: 700; cursor: pointer; }
        button { background: #111827; color: #fff; }
        .link { background: #e5e7eb; color: #111827; }
        .err { margin: 12px 0 0; color: #b91c1c; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Nuovo cliente</h1>
        <?= validation_errors('<div class="err">', '</div>') ?>

        <form method="post" action="<?= site_url('clienti/salva') ?>">
            <label>Ragione sociale</label>
            <input type="text" name="ragione_sociale" value="<?= html_escape($this->input->post('ragione_sociale', true)) ?>" required>

            <label>Partita IVA</label>
            <input type="text" name="partita_iva" value="<?= html_escape($this->input->post('partita_iva', true)) ?>">

            <label>Codice fiscale</label>
            <input type="text" name="codice_fiscale" value="<?= html_escape($this->input->post('codice_fiscale', true)) ?>">

            <label>Indirizzo</label>
            <input type="text" name="indirizzo" value="<?= html_escape($this->input->post('indirizzo', true)) ?>">

            <label>Note</label>
            <textarea name="note"><?= html_escape($this->input->post('note', true)) ?></textarea>

            <button type="submit">Salva</button>
            <a class="link" href="<?= site_url('clienti') ?>">Annulla</a>
        </form>
    </div>
</body>
</html>
