<article class="card pad podium" data-player="{{ CURRENT_PLAYER_NAMES[$turn->player_id] }}">
    <div class="chip">AI</div>
    <div class="row">
        <div class="robot"><div class="avatar">🤖</div><strong>{{ CURRENT_PLAYER_NAMES[$turn->player_id] }}</strong></div>
        <div class="score-badge score-0" data-round-score>{{ $turn->score }}</div>
    </div>
    <div class="fieldlbl">ISBN</div>
    <div class="mono" data-isbn>{{ $turn->isbn }}</div>
    <div class="fieldlbl" style="margin-top:8px">Title</div>
    <div data-title>{{ $turn->title }}</div>
    <div class="fieldlbl" style="margin-top:8px">Author</div>
    <div data-title>{{ $turn->author }}</div>
    @if(!empty($turn->canonical_title))
        <div class="card pad" style="margin-top:10px; background:#0a0f1e;border-color:var(--border);" data-cmatch>
            <div class="fieldlbl">Matched title</div>
            <div class="mono" data-canonical>{{ $turn->canonical_title }}</div>
        </div>
    @endif
</article>
