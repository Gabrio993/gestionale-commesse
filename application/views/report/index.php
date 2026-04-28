<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= html_escape($titolo ?? 'Report') ?></title>
    <?php $this->load->view('partials/ui'); ?>
    <style>
        .report-charts {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            margin: 14px 0 6px;
            align-items: stretch;
        }
        .report-chart {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 12px;
            width: 100%;
            max-width: none;
            min-height: 440px;
            display: flex;
            flex-direction: column;
        }
        .report-chart h2 {
            margin: 0 0 6px 0;
            font-size: 16px;
        }
        .report-chart p {
            margin: 0 0 10px 0;
            color: var(--muted);
            font-size: 13px;
        }
        .report-chart-svg {
            width: 100%;
            min-height: 240px;
            max-width: none;
            margin: 0;
            display: flex;
            flex: 1 1 auto;
        }
        .donut-layout {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 12px;
            height: 100%;
            flex: 1 1 auto;
        }
        .donut-visual {
            flex: 1 1 auto;
            display: grid;
            place-items: center;
            min-height: 320px;
        }
        .donut-visual svg {
            width: 260px;
            max-width: 100%;
            height: auto;
            display: block;
        }
        .donut-legend {
            display: grid;
            gap: 8px;
            align-content: end;
            justify-content: start;
            align-self: flex-start;
            margin-top: auto;
            font-size: 12px;
            color: var(--muted);
        }
        .report-legend {
            display: grid;
            gap: 6px;
            margin-top: 10px;
            font-size: 12px;
            color: var(--muted);
        }
        .report-legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .report-legend-swatch {
            width: 12px;
            height: 12px;
            border-radius: 999px;
            flex: 0 0 12px;
        }
        .report-empty-chart {
            min-height: 240px;
            display: grid;
            place-items: center;
            color: var(--muted);
            border: 1px dashed var(--border);
            border-radius: 14px;
            background: var(--surface-soft);
        }
        @media (max-width: 900px) {
            .report-charts {
                grid-template-columns: 1fr;
            }
            .donut-layout {
                gap: 10px;
            }
            .donut-legend {
                align-self: stretch;
            }
        }
    </style>
</head>
<body>
    <?php $this->load->view('partials/navigation'); ?>

    <div class="app-wrap">
        <div class="app-card">
            <div class="page-head">
                <div>
                    <h1 class="page-title"><?= html_escape($titolo ?? 'Report') ?></h1>
                    <p class="page-subtitle">Filtra per oggi, ultimi 30 giorni o per un periodo personalizzato e, se serve, per commessa.</p>
                </div>
                <?php if ($globale): ?>
                    <div class="actions-inline">
                        <a class="btn secondary" href="<?= site_url('report/utenti') ?>">Per utenti</a>
                        <a class="btn secondary" href="<?= site_url('report/commesse') ?>">Per commesse</a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="notice">
                Periodo predefinito: oggi. Puoi allargare subito il report agli ultimi 30 giorni o usare il calendario.
            </div>

            <!-- I filtri sono in GET per permettere di condividere l'URL del report. -->
            <form method="get" action="<?= site_url('report') ?>" class="form-grid" style="margin-bottom:18px;">
                <div class="summary-grid" style="margin:0;">
                    <div class="field">
                        <label>Dal</label>
                        <input type="date" name="dal" value="<?= html_escape($filtri['dal'] ?? '') ?>">
                    </div>
                    <div class="field">
                        <label>Al</label>
                        <input type="date" name="al" value="<?= html_escape($filtri['al'] ?? '') ?>">
                    </div>
                    <div class="field">
                        <label>Commessa</label>
                        <select name="commessa_id">
                            <option value="">Tutte le commesse</option>
                            <?php foreach ($commesse as $commessa): ?>
                                <option value="<?= (int) $commessa->id ?>" <?= ! empty($filtri['commessa_id']) && (int) $filtri['commessa_id'] === (int) $commessa->id ? 'selected' : '' ?>>
                                    <?= html_escape(($commessa->codice ? $commessa->codice . ' - ' : '') . $commessa->attivita) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="actions-inline">
                    <button class="btn primary" type="submit">Applica</button>
                    <a class="btn secondary" href="<?= site_url('report') ?>">Oggi</a>
                    <a class="btn secondary" href="<?= site_url('report?dal=' . date('Y-m-d', strtotime('-30 days')) . '&al=' . date('Y-m-d')) ?>">Ultimi 30 giorni</a>
                </div>
            </form>

            <!-- Card con i totali principali del periodo filtrato. -->
            <div class="summary-grid">
                <div class="summary-card">
                    <div class="label">Totale ore filtrate</div>
                    <div class="value"><?= number_format((float) $totale_ore, 2, ',', '.') ?></div>
                </div>
                <div class="summary-card">
                    <div class="label">Registrazioni filtrate</div>
                    <div class="value"><?= (int) ($totale_registrazioni ?? 0) ?></div>
                </div>
                <div class="summary-card">
                    <div class="label">Dal</div>
                    <div class="value"><?= html_escape($filtri['dal'] ?? 'n.d.') ?></div>
                </div>
                <div class="summary-card">
                    <div class="label">Al</div>
                    <div class="value"><?= html_escape($filtri['al'] ?? 'n.d.') ?></div>
                </div>
            </div>

            <div class="report-charts">
                <div class="report-chart">
                    <h2>Ore per commessa</h2>
                    <p>Il peso delle commesse nel periodo filtrato.</p>
                    <?php if (! empty($grafico_commesse['labels'])): ?>
                        <div id="graficoCommessa" class="report-chart-svg" aria-label="Grafico a donut delle ore per commessa"></div>
                    <?php else: ?>
                        <div class="report-empty-chart">Nessun dato disponibile per il grafico a donut.</div>
                    <?php endif; ?>
                </div>
                <div class="report-chart">
                    <h2>Ore per giorno</h2>
                    <p>L'andamento giornaliero nel periodo filtrato.</p>
                    <?php if (! empty($grafico_giorni['labels'])): ?>
                        <div id="graficoGiorni" class="report-chart-svg" aria-label="Grafico a barre delle ore per giorno"></div>
                    <?php else: ?>
                        <div class="report-empty-chart">Nessun dato disponibile per il grafico a barre.</div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($globale): ?>
                <!-- Report sintetico degli utenti: visibile solo ad admin e superadmin. -->
                <div class="section">
                    <h2>Riepilogo per utenti</h2>
                    <div class="table-wrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Utente</th>
                                    <th>Email</th>
                                    <th>Ruolo</th>
                                    <th>Ore</th>
                                    <th>Dettaglio</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (! empty($riepilogo_utenti)): ?>
                                    <?php foreach ($riepilogo_utenti as $riga): ?>
                                        <tr>
                                            <td><?= html_escape(trim($riga->nome . ' ' . $riga->cognome)) ?></td>
                                            <td><?= html_escape($riga->email) ?></td>
                                            <td><span class="badge"><?= html_escape($riga->ruolo) ?></span></td>
                                            <td><strong><?= number_format((float) $riga->totale_ore, 2, ',', '.') ?></strong></td>
                                            <td><a class="btn secondary" href="<?= site_url('ore/utente/' . (int) $riga->id) ?>">Apri</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="5">Nessun dato disponibile.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Report per commessa: utile per leggere la distribuzione del lavoro. -->
            <div class="section">
                <h2>Riepilogo per commesse</h2>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Commessa</th>
                                <th>Attività</th>
                                <th>Cliente</th>
                                <th>Ore</th>
                                <?php if ($globale): ?><th>Apri</th><?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (! empty($riepilogo_commesse)): ?>
                                <?php foreach ($riepilogo_commesse as $riga): ?>
                                    <tr>
                                        <td><?= html_escape($riga->codice) ?></td>
                                        <td><?= html_escape($riga->attivita) ?></td>
                                        <td><?= html_escape($riga->cliente_ragione_sociale) ?></td>
                                        <td><strong><?= number_format((float) $riga->totale_ore, 2, ',', '.') ?></strong></td>
                                        <?php if ($globale): ?><td><a class="btn secondary" href="<?= site_url('commesse/dettaglio/' . (int) $riga->id) ?>">Apri</a></td><?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="<?= $globale ? 5 : 4 ?>">Nessun dato disponibile.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Ultime registrazioni: la vista piu utile per controlli rapidi. -->
            <div class="section">
                <h2>Ultime registrazioni</h2>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Ore</th>
                                <th>Commessa</th>
                                <th>Attività</th>
                                <th>Cliente</th>
                                <?php if ($globale): ?><th>Utente</th><?php endif; ?>
                                <th>Note</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (! empty($ultime_registrazioni)): ?>
                                <?php foreach ($ultime_registrazioni as $riga): ?>
                                    <tr>
                                        <td><?= html_escape($riga->data_lavoro) ?></td>
                                        <td><strong><?= html_escape($riga->ore) ?></strong></td>
                                        <td><?= html_escape($riga->commessa_codice) ?></td>
                                        <td><?= html_escape($riga->commessa_attivita) ?></td>
                                        <td><?= html_escape($riga->cliente_ragione_sociale) ?></td>
                                        <?php if ($globale): ?><td><?= html_escape(trim($riga->utente_nome . ' ' . $riga->utente_cognome)) ?></td><?php endif; ?>
                                        <td><?= html_escape($riga->descrizione) ?></td>
                                        <td>
                                            <div class="actions-inline">
                                                <a class="btn secondary" href="<?= site_url('ore/modifica/' . (int) $riga->id) ?>">Modifica</a>
                                                <form method="post" action="<?= site_url('ore/elimina/' . (int) $riga->id) ?>" onsubmit="return confirm('Eliminare questa registrazione?');">
                                                    <button class="btn danger" type="submit">Elimina</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="<?= $globale ? 8 : 7 ?>">Nessuna registrazione presente nel periodo selezionato.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const commesseLabels = <?= json_encode($grafico_commesse['labels'] ?? array(), JSON_UNESCAPED_UNICODE) ?>;
            const commesseValues = <?= json_encode($grafico_commesse['values'] ?? array(), JSON_UNESCAPED_UNICODE) ?>;
            const giorniLabels = <?= json_encode($grafico_giorni['labels'] ?? array(), JSON_UNESCAPED_UNICODE) ?>;
            const giorniValues = <?= json_encode($grafico_giorni['values'] ?? array(), JSON_UNESCAPED_UNICODE) ?>;

            const palette = [
                '#111827',
                '#2563eb',
                '#0f766e',
                '#f59e0b',
                '#7c3aed',
                '#dc2626',
                '#0284c7',
                '#16a34a',
                '#ea580c',
                '#4b5563'
            ];

            function escapeHtml(value) {
                return String(value)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function fmtHours(value) {
                return Number(value || 0).toFixed(2).replace('.', ',');
            }

            function renderDonut(labels, values) {
                const total = values.reduce((sum, value) => sum + Number(value || 0), 0);
                const size = 320;
                const center = size / 2;
                const radius = 150;
                const stroke = 18;
                const circumference = 2 * Math.PI * radius;

                if (!total) {
                    return '<div class="report-empty-chart">Nessun dato disponibile.</div>';
                }

                let offset = 0;
                const segments = values.map((value, index) => {
                    const portion = Number(value || 0) / total;
                    const segment = circumference * portion;
                    const color = palette[index % palette.length];
                    const dashOffset = circumference - offset;
                    offset += segment;
                    return `<circle cx="${center}" cy="${center}" r="${radius}" fill="none" stroke="${color}" stroke-width="${stroke}" stroke-dasharray="${segment} ${circumference - segment}" stroke-dashoffset="${dashOffset}" transform="rotate(-90 ${center} ${center})"></circle>`;
                }).join('');

                const centerText = `
                    <text x="${center}" y="${center - 8}" text-anchor="middle" font-size="28" font-weight="700" fill="#111827">${fmtHours(total)}</text>
                    <text x="${center}" y="${center + 20}" text-anchor="middle" font-size="16" font-weight="600" fill="#6b7280">Ore totali</text>
                `;

                const legend = labels.map((label, index) => {
                    const color = palette[index % palette.length];
                    return `
                        <div class="report-legend-item">
                            <span class="report-legend-swatch" style="background:${color}"></span>
                            <span>${escapeHtml(label)}: <strong>${fmtHours(values[index])}</strong></span>
                        </div>
                    `;
                }).join('');

                return `
                    <div class="donut-layout">
                        <div class="donut-visual">
                            <svg viewBox="0 0 ${size} ${size}" width="${size}" height="${size}" role="img" aria-hidden="true">
                                ${segments}
                                ${centerText}
                            </svg>
                        </div>
                        <div class="donut-legend report-legend">${legend}</div>
                    </div>
                `;
            }

            function renderBars(labels, values) {
                const max = Math.max.apply(null, values.map(function (value) {
                    return Number(value || 0);
                }));

                if (!max) {
                    return '<div class="report-empty-chart">Nessun dato disponibile.</div>';
                }

                const width = 320;
                const height = 180;
                const padding = { top: 10, right: 10, bottom: 30, left: 18 };
                const chartWidth = width - padding.left - padding.right;
                const chartHeight = height - padding.top - padding.bottom;
                const gap = 6;
                const barWidth = Math.max(10, (chartWidth - (gap * (values.length - 1))) / values.length);
                const scale = chartHeight / max;

                const gridLines = [0, 0.25, 0.5, 0.75, 1].map(function (fraction) {
                    const y = padding.top + chartHeight - (chartHeight * fraction);
                    return `<line x1="${padding.left}" y1="${y}" x2="${width - padding.right}" y2="${y}" stroke="#e5e7eb" stroke-width="1"></line>`;
                }).join('');

                const bars = values.map(function (value, index) {
                    const h = Number(value || 0) * scale;
                    const x = padding.left + (index * (barWidth + gap));
                    const y = padding.top + chartHeight - h;
                    const label = labels[index];
                    return `
                        <rect x="${x}" y="${y}" width="${barWidth}" height="${h}" rx="8" fill="#111827"></rect>
                        <text x="${x + (barWidth / 2)}" y="${padding.top + chartHeight + 13}" text-anchor="middle" font-size="8" fill="#6b7280">
                            ${escapeHtml(label)}
                        </text>
                        <text x="${x + (barWidth / 2)}" y="${y - 3}" text-anchor="middle" font-size="8" font-weight="700" fill="#111827">
                            ${fmtHours(value)}
                        </text>
                    `;
                }).join('');

                return `
                    <svg viewBox="0 0 ${width} ${height}" role="img" aria-hidden="true" preserveAspectRatio="none">
                        ${gridLines}
                        ${bars}
                    </svg>
                `;
            }

            const commessaBox = document.getElementById('graficoCommessa');
            if (commessaBox && commesseLabels.length) {
                commessaBox.innerHTML = renderDonut(commesseLabels, commesseValues);
            }

            const giorniBox = document.getElementById('graficoGiorni');
            if (giorniBox && giorniLabels.length) {
                giorniBox.innerHTML = renderBars(giorniLabels, giorniValues);
            }
        })();
    </script>
</body>
</html>
