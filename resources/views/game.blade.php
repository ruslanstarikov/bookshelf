<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Jeopardy: You vs Robots (HTML Mock)</title>
    @include('inline-styles')
</head>
<body>
<div class="wrap">
    <!-- Header / Scoreboard -->
    <header>
        <div class="brand">
            <div class="badge">🎯</div>
            <div>
                <h1>Jeopardy: You vs Robots</h1>
                <div class="round">Round <span id="round-number">1</span></div>
            </div>
        </div>
        <div class="scores" id="scoreboard">
            @foreach($players as $player)
                <div class="scorebox" data-player="{{ $player->name }}">
                    <div class="name">{{ $player->name }}</div>
                    <div class="score" data-score>{{ $player->total_score }}</div>
                </div>
            @endforeach
        </div>
    </header>

    <!-- Clue Card -->
    <section class="card pad form" id="clue-card">
        <label for="clue">Your clue about the book</label>
        <textarea id="clue" placeholder="e.g., A Victorian-era tale of an orphan who meets a miserly guardian and a mysterious convict."></textarea>
        <div class="actions">
            <button class="btn-primary" id="play">Play Round</button>
            <button class="btn-muted" id="clear">Clear</button>
        </div>
    </section>

    <!-- Podiums / Current Round -->
    <section class="grid" id="board" aria-live="polite" aria-busy="false">
        <!-- Each podium -->
        @if(empty($last_round))
            @include('blank-current')
        @else
        @foreach($last_round->turns as $turn)
            @include('current-round', ['turn' => $turn])
        @endforeach
        @endif
    </section>

    <!-- History -->
    <section>
        <h2 class="history-title">Previous Rounds</h2>
        <div id="history" class="history-list">
            @foreach($rounds as $round)
                @include('round', ['round' => $round])
            @endforeach
        </div>
    </section>
</div>
</body>
</html>
