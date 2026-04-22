<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuova commessa</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; min-height: 100vh; display: grid; place-items: center; background: #f4f6f8; color: #1f2937; }
        .card { width: min(92vw, 560px); background: #fff; border: 1px solid #e5e7eb; border-radius: 16px; padding: 28px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06); }
        label { display: block; margin: 14px 0 6px; font-weight: 700; }
        input, textarea, select { width: 100%; box-sizing: border-box; padding: 12px; border: 1px solid #d1d5db; border-radius: 10px; }
        textarea { min-height: 110px; }
        button, .link { display: inline-block; margin-top: 16px; padding: 12px 16px; border-radius: 10px; border: 0; text-decoration: none; font-weight: 700; cursor: pointer; }
        button { background: #111827; color: #fff; }
        .link { background: #e5e7eb; color: #111827; }
        .err { margin: 12px 0 0; color: #b91c1c; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Nuova commessa</h1>
        <?= validation_errors('<div class="err">', '</div>') ?>

        <form method="post" action="<?= site_url('commesse/salva') ?>">
            <label>Cliente</label>
            <select name="cliente_id" required>
                <option value="">Seleziona un cliente</option>
                <?php foreach ($clienti as $cliente): ?>
                    <option value="<?= (int) $cliente->id ?>" <?= $this->input->post('cliente_id', true) == $cliente->id ? 'selected' : '' ?>>
                        <?= html_escape($cliente->ragione_sociale) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Codice commessa</label>
            <input type="text" name="codice" value="<?= html_escape($this->input->post('codice', true)) ?>">

            <label>Nome</label>
            <input type="text" name="nome" value="<?= html_escape($this->input->post('nome', true)) ?>" required>

            <label>Descrizione</label>
            <textarea name="descrizione"><?= html_escape($this->input->post('descrizione', true)) ?></textarea>

            <button type="submit">Salva</button>
            <a class="link" href="<?= site_url('commesse') ?>">Annulla</a>
        </form>
    </div>
</body>
</html>
