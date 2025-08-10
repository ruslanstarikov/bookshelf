<article class="card pad podium" data-player="HAL-9000">
    <div class="chip">Robot</div>
    <div class="row">
        <div class="robot"><div class="avatar">🤖</div><strong>HAL-9000</strong></div>
        <div class="score-badge score-0" data-round-score>0</div>
    </div>
    <div class="fieldlbl">ISBN</div>
    <div class="mono" data-isbn>—</div>
    <div class="fieldlbl" style="margin-top:8px">Title</div>
    <div data-title>—</div>
    @if(!empty($turn->canonical_title))
        <div class="card pad" style="margin-top:10px; background:#0a0f1e;border-color:var(--border);" data-cmatch>
            <div class="fieldlbl">Matched title</div>
            <div class="mono" data-canonical>{{ $turn->canonical }}</div>
        </div>
    @endif
</article>

<article class="card pad podium" data-player="RoboLit">
    <div class="chip">Robot</div>
    <div class="row">
        <div class="robot"><div class="avatar">🛸</div><strong>RoboLit</strong></div>
        <div class="score-badge score-0" data-round-score>0</div>
    </div>
    <div class="fieldlbl">ISBN</div>
    <div class="mono" data-isbn>—</div>
    <div class="fieldlbl" style="margin-top:8px">Title</div>
    <div data-title>—</div>
    <div class="card pad" style="margin-top:10px; background:#0a0f1e;border-color:var(--border); display:none" data-cmatch>
        <div class="fieldlbl">Google Books matched title</div>
        <div class="mono" data-canonical>—</div>
    </div>
</article>

<article class="card pad podium" data-player="BookTron">
    <div class="chip">Robot</div>
    <div class="row">
        <div class="robot"><div class="avatar">📚</div><strong>BookTron</strong></div>
        <div class="score-badge score-0" data-round-score>0</div>
    </div>
    <div class="fieldlbl">ISBN</div>
    <div class="mono" data-isbn>—</div>
    <div class="fieldlbl" style="margin-top:8px">Title</div>
    <div data-title>—</div>
    <div class="card pad" style="margin-top:10px; background:#0a0f1e;border-color:var(--border); display:none" data-cmatch>
        <div class="fieldlbl">Google Books matched title</div>
        <div class="mono" data-canonical>—</div>
    </div>
</article>
