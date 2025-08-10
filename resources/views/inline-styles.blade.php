<style>
    :root{
        --bg0:#0b1220; --bg1:#0f172a; --panel:#111827; --ink:#e5e7eb; --muted:#94a3b8; --blue:#3b82f6; --green:#22c55e; --yellow:#f59e0b; --red:#ef4444; --border:#1f2937;
    }
    *{box-sizing:border-box}
    body{margin:0;background:linear-gradient(180deg,var(--bg0),var(--bg1));color:var(--ink);font:16px/1.5 system-ui,-apple-system,Segoe UI,Roboto,Inter,Arial,sans-serif}
    .wrap{max-width:1100px;margin:0 auto;padding:32px 16px}
    header{display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;border-bottom:1px solid var(--border);padding-bottom:16px}
    .brand{display:flex;gap:12px;align-items:center}
    .badge{display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:10px;background:var(--blue)}
    h1{margin:0;font-weight:800;font-size:22px}
    .round{font-size:12px;color:var(--muted)}
    .scores{display:flex;gap:28px}
    .scorebox{text-align:center}
    .scorebox .name{font-size:12px;color:var(--muted)}
    .scorebox .score{font-size:24px;font-weight:800}

    .card{background:linear-gradient(180deg,#0e1530cc,#0d1226bf);border:1px solid var(--border);border-radius:16px;box-shadow:0 6px 24px rgba(0,0,0,.25)}
    .pad{padding:18px}

    .form label{display:block;font-size:13px;color:#bfdbfe;margin-bottom:6px}
    textarea{width:100%;min-height:110px;padding:12px;border-radius:12px;border:1px solid var(--border);background:#0b1022;color:var(--ink)}
    .actions{margin-top:10px;display:flex;gap:10px}
    button{cursor:pointer;border:0;border-radius:12px;padding:10px 14px;font-weight:700}
    .btn-primary{background:var(--blue);color:white}
    .btn-muted{background:#334155;color:#e2e8f0}

    .grid{display:grid;grid-template-columns:1fr;gap:16px}
    @media (min-width: 900px){.grid{grid-template-columns:repeat(3,1fr)}}

    .podium{position:relative}
    .chip{position:absolute;right:12px;top:-10px;background:#1f2937;color:#cbd5e1;border:1px solid var(--border);padding:4px 10px;border-radius:999px;font-size:12px}
    .row{display:flex;justify-content:space-between;align-items:center;margin-bottom:10px}
    .robot{display:flex;gap:10px;align-items:center}
    .avatar{width:42px;height:42px;border-radius:50%;background:#1f2937;display:grid;place-items:center;font-size:20px}

    .fieldlbl{font-size:12px;color:var(--muted)}
    .mono{font-family: ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace}

    .score-badge{display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:999px;font-weight:800}
    .score-0{background:var(--red);color:white}
    .score-1{background:var(--yellow);color:#111}
    .score-2{background:var(--green);color:white}

    .history-title{margin:28px 0 12px 0;font-weight:700}
    .history-list{display:flex;flex-direction:column;gap:12px}
    .history-item{border:1px solid var(--border);border-radius:12px;padding:12px;background:#0b1120}
    .history-grid{display:grid;grid-template-columns:1fr;gap:10px}
    @media (min-width: 900px){.history-grid{grid-template-columns:repeat(3,1fr)}}
    .item-row{display:flex;justify-content:space-between;align-items:center;border:1px solid var(--border);border-radius:10px;padding:8px;background:#0a0f1e}
    .muted{color:var(--muted)}
</style>
