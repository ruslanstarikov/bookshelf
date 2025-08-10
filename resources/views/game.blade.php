<?php
const CURRENT_PLAYER_NAMES = [
    1 => 'Mistral',
    2 => 'ChatGPT Mini',
    3 => 'ChatGPT'
];
const PLAYER_NAMES = [
    1 => 'Mistral',
    2 => 'ChatGPT Mini',
    3 => 'ChatGPT'
];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Jeopardy: You vs Robots (HTML Mock)</title>
    @include('inline-styles')
    <style>
        /* overlay spinner */
        #loading-overlay { position: fixed; inset: 0; display:none; align-items:center; justify-content:center; background:rgba(0,0,0,.55); z-index:9999; }
        .spinner { width:56px; height:56px; border-radius:50%; border:6px solid #fff; border-top-color: transparent; animation: spin 1s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg) } }
        /* modal base */
        .modal { position: fixed; inset: 0; display:none; align-items:center; justify-content:center; background:rgba(0,0,0,.45); z-index:10000; }
        .modal .panel { background:#fff; max-width:520px; width:90%; border-radius:14px; padding:20px; box-shadow: 0 10px 35px rgba(0,0,0,.25); }
        .modal .panel h3 { margin:0 0 10px; }
        .modal .panel .actions { display:flex; gap:10px; justify-content:flex-end; margin-top:16px; }
        .hidden { display:none !important; }
        #error-title {color: black !important;}
        #error-message {color: black !important;}
    </style>
    <script src="https://unpkg.com/htmx.org@1.9.12"></script>
    <script>
        // Attach CSRF header to all HTMX requests (Laravel)
        document.addEventListener('htmx:configRequest', (e) => {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            e.detail.headers['X-CSRF-TOKEN'] = token;
        });

        // Helpers to show/hide UI
        function show(el){ el.style.display='flex'; }
        function hide(el){ el.style.display='none'; }

        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('play-form');
            const playBtn = document.getElementById('play');
            const clearBtn = document.getElementById('clear');
            const clue = document.getElementById('clue');
            const overlay = document.getElementById('loading-overlay');
            const errModal = document.getElementById('error-modal');
            const errMsg = document.getElementById('error-message');
            const errOk = document.getElementById('error-ok');

            // Show spinner before request
            form.addEventListener('htmx:beforeRequest', () => {
                playBtn.disabled = true;
                show(overlay);
            });

            // Hide spinner when request fully finishes (success or error)
            form.addEventListener('htmx:afterRequest', () => {
                playBtn.disabled = false;
                hide(overlay);
            });

            // On successful response: if {status:"ok"} => reload
            form.addEventListener('htmx:afterOnLoad', (evt) => {
                try {
                    const data = JSON.parse(evt.detail.xhr.responseText || '{}');
                    if (data.status === 'ok') {
                        window.location.reload();
                        return;
                    }
                    // Otherwise treat as error
                    errMsg.textContent = data.message || 'Sorry, your clue was rejected. Please try again.';
                    show(errModal);
                } catch (_) {
                    // Non-JSON or unexpected payload -> error modal
                    errMsg.textContent = 'Unexpected response from the server.';
                    show(errModal);
                }
            });

            // Network / 5xx errors
            form.addEventListener('htmx:responseError', () => {
                errMsg.textContent = 'Server error. Please try again.';
                show(errModal);
            });

            // Clear textarea on "OK" from error modal and close it
            errOk.addEventListener('click', () => {
                clue.value = '';
                hide(errModal);
                clue.focus();
            });

            // Clear button
            clearBtn.addEventListener('click', () => {
                clue.value = '';
                clue.focus();
            });
        });
    </script>
</head>
<body>
<div class="wrap">
    <!-- Header / Scoreboard -->
    <header>
        <div class="brand">
            <div>
                <h1>You vs Robots</h1>
                <form method="GET" action="{{ route('game.reset') }}" class="inline">
                    @csrf
                    <button type="submit" class="btn-muted">Start Over</button>
                </form>
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
        <form id="play-form"
              hx-post="/game/round"
              hx-trigger="submit"
              hx-swap="none">
            @csrf
            <label for="clue">Your clue about the book</label>
            <textarea name="clue" id="clue" placeholder="e.g., A Victorian-era tale of an orphan who meets a miserly guardian and a mysterious convict."></textarea>
            <div class="actions">
                <button class="btn-primary" id="play" type="submit">Play Round</button>
                <button class="btn-muted" id="clear" type="button">Clear</button>
            </div>
        </form>
    </section>
    <br />
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
    @if(count($rounds) > 0)
    <section>
        <h2 class="history-title">Previous Rounds</h2>
        <div id="history" class="history-list">
            @foreach($rounds as $round)
                @include('round', ['round' => $round])
            @endforeach
        </div>
    </section>
    @endif
</div>
<div id="loading-overlay" aria-hidden="true">
    <div class="spinner" role="status" aria-label="Working…"></div>
</div>

<!-- Error modal -->
<div class="modal" id="error-modal" role="dialog" aria-modal="true" aria-labelledby="error-title">
    <div class="panel">
        <h3 id="error-title">Something went wrong</h3>
        <p id="error-message">Sorry, your clue was rejected. Please try again.</p>
        <div class="actions">
            <button class="btn-primary" id="error-ok" type="button">OK</button>
        </div>
    </div>
</div>
</body>
</html>
