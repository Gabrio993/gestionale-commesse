<style>
    :root {
        --bg: #f4f6f8;
        --surface: #ffffff;
        --surface-soft: #f9fafb;
        --text: #1f2937;
        --muted: #6b7280;
        --border: #e5e7eb;
        --accent: #111827;
        --accent-soft: #374151;
        --success: #065f46;
        --danger: #b91c1c;
        --shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
    }

    * { box-sizing: border-box; }
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        min-height: 100vh;
        background:
            radial-gradient(circle at top left, rgba(17, 24, 39, 0.06), transparent 28%),
            radial-gradient(circle at bottom right, rgba(31, 41, 55, 0.05), transparent 26%),
            var(--bg);
        color: var(--text);
    }

    .app-wrap {
        width: min(calc(100vw - 40px), 1600px);
        margin: 0 auto;
        padding: 24px 0 40px;
    }
    .app-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 18px;
        padding: 26px;
        box-shadow: var(--shadow);
    }

    .page-head {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        align-items: flex-start;
        margin-bottom: 18px;
    }

    .page-title { margin: 0 0 8px; font-size: 28px; }
    .page-subtitle {  color: var(--muted); }

    .app-topbar {
        position: sticky;
        top: 0;
        z-index: 20;
        backdrop-filter: blur(12px);
        background: rgba(244, 246, 248, 0.9);
        border-bottom: 1px solid rgba(229, 231, 235, 0.8);
    }

    .app-topbar-inner {
        width: min(calc(100vw - 40px), 1600px);
        margin: 0 auto;
        padding: 16px 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }

    .brand {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .brand strong { font-size: 18px; }
    .brand span { color: var(--muted); font-size: 13px; }

    .nav-links {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
    }

    .nav-links a,
    .nav-links span.link-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border-radius: 999px;
        text-decoration: none;
        border: 1px solid var(--border);
        background: rgba(255,255,255,0.75);
        color: var(--text);
        font-weight: 700;
        font-size: 14px;
    }

    .nav-links a.primary {
        background: var(--accent);
        color: white;
        border-color: var(--accent);
    }

    .nav-links a.secondary {
        background: #fff;
    }

    .user-chip {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        border-radius: 999px;
        border: 1px solid var(--border);
        background: #fff;
    }

    .avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        color: #fff;
        background: linear-gradient(135deg, var(--accent), var(--accent-soft));
        font-weight: 800;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 999px;
        background: #e5e7eb;
        font-size: 12px;
        font-weight: 700;
    }

    .badge.success { background: #d1fae5; color: var(--success); }
    .badge.danger { background: #fee2e2; color: var(--danger); }

    .summary-grid {
        display: grid;
        gap: 14px;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        margin: 18px 0 24px;
    }

    .summary-card {
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 18px;
        background: linear-gradient(180deg, #fff, #fbfbfb);
    }

    .summary-card .label { color: var(--muted); font-size: 13px; margin-bottom: 8px; }
    .summary-card .value { font-size: 18px; font-weight: 600; margin-bottom: 10px; }

    .section {
        margin-top: 18px;
    }

    .section h2 {
        margin: 0 0 12px;
        font-size: 20px;
    }

    .table-wrap { overflow-x: auto; border: 1px solid var(--border); border-radius: 16px; }

    table.table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
    }

    .table th, .table td {
        padding: 14px 12px;
        border-bottom: 1px solid var(--border);
        text-align: left;
        vertical-align: top;
    }

    .table th {
        background: var(--surface-soft);
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: .03em;
        color: var(--muted);
    }

    .actions-inline {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 14px;
        border-radius: 12px;
        border: 1px solid transparent;
        text-decoration: none;
        cursor: pointer;
    }

    .btn.primary { background: var(--accent); color: #fff; }
    .btn.secondary { background: #fff; color: var(--text); border-color: var(--border); }
    .btn.danger { background: #fee2e2; color: var(--danger); border-color: #fecaca; }

    .form-grid {
        display: grid;
        gap: 14px;
    }

    .field label {
        display: block;
        margin-bottom: 6px;
        font-weight: 700;
    }

    .field input, .field textarea, .field select {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: #fff;
        font: inherit;
    }

    .field textarea { min-height: 120px; resize: vertical; }

    .notice {
        padding: 14px 16px;
        border-radius: 14px;
        margin: 0 0 16px;
        border: 1px solid var(--border);
        background: #fff;
    }

    .notice.error {
        background: #fef2f2;
        border-color: #fecaca;
        color: var(--danger);
    }

    .notice.success {
        background: #ecfdf5;
        border-color: #a7f3d0;
        color: var(--success);
    }

    @media (max-width: 760px) {
        .app-wrap { width: min(calc(100vw - 24px), 1600px); padding: 16px 0 30px; }
        .app-card { padding: 18px; }
        .page-title { font-size: 24px; }
        .nav-links { width: 100%; }
    }
</style>
