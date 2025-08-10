<?php
    const PLAYER_NAMES = [
		1 => 'Mistral',
        2 => 'ChatGPT Mini',
        3 => 'ChatGPT'
    ]
?>
<div class="history-item">
    <div class="muted" style="margin-bottom:8px">Round {{ $round->round }}</div>
    <div class="history-grid">
        @foreach($round->turns as $turn)
        <div class="item-row"><div><strong>{{ PLAYER_NAMES[$turn->player_id] }}</strong><div class="muted mono">{{$turn->isbn }} -
            {{ $turn->title }} - {{ $turn->author }}</div></div><span class="score-badge score-{{$turn->score}}">{{$turn->score}}</span></div>
        @endforeach
    </div>
</div>
