// app/Http/Controllers/GameController.php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GameService;

class GameController extends Controller
{
    public function index(Request $request)
    {
        // seed scoreboard & history in session
        $request->session()->put('game.history', $request->session()->get('game.history', []));
        $request->session()->put('game.scores', $request->session()->get('game.scores', [
            'HAL-9000' => 0, 'RoboLit' => 0, 'BookTron' => 0,
        ]));

        return view('game.index', [
            'round'   => count($request->session()->get('game.history')) + 1,
            'scores'  => $request->session()->get('game.scores'),
            'history' => $request->session()->get('game.history'),
            'current' => null,
        ]);
    }

    public function round(Request $request, GameService $service)
    {
        $clue = trim((string) $request->input('clue', ''));
        abort_if($clue === '', 422, 'Clue required');

        // Call your 3 agents + score (no web lookup by agents; Google Books only in validation)
        $results = $service->playRound($clue);

        // update session totals + history
        $scores  = $request->session()->get('game.scores', []);
        foreach ($results as $r) {
            $scores[$r['guess']['name']] = ($scores[$r['guess']['name']] ?? 0) + $r['score'];
        }
        $history = $request->session()->get('game.history', []);
        $history[] = $results;

        $request->session()->put('game.scores', $scores);
        $request->session()->put('game.history', $history);

        // HTMX: return just the parts we want to update
        // - the board (podiums) and the header scores
        // Use out-of-band swaps to touch multiple targets at once.
        return response()->view('game.partials.board', [
            'results' => $results,
        ])->header('HX-Trigger', json_encode([
            'updateScores' => ['scores' => $scores, 'round' => count($history) + 1],
            'appendHistory' => ['roundResults' => $results, 'index' => count($history)],
        ]));
    }
}
