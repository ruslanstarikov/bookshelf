<?php

namespace App\Http\Controllers;

use App\Models\Round;
use App\Models\Turn;
use Illuminate\Http\Request;
use App\Services\GameService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GameController extends Controller
{
    public function indexIndex()
    {
        return redirect('/game');
    }
    public function index(Request $request)
    {
        $sessionId = $request->session()->get('session_id');

        if (!$sessionId) {
            $sessionId = Str::uuid()->toString();
            $request->session()->put('session_id', $sessionId);
        }

        $latestRound = Round::with('turns')->where('session_id', $sessionId)
            ->orderByDesc('round')
            ->first();

        $previousRounds = Round::with('turns')->where('session_id', $sessionId)
            ->where('id', '!=', optional($latestRound)->id)
            ->orderBy('round', 'asc')
            ->get();

        $scores = Turn::query()
            ->whereIn('round_id', Round::where('session_id', $sessionId)->select('id'))
            ->whereNotNull('player_id')                     // optional
            ->select('player_id', DB::raw('SUM(score) AS total_score'))
            ->groupBy('player_id')
            ->orderByDesc('total_score')
            ->get();
        foreach($scores as &$score)
        {
            $score->name = GameService::ID_TO_PLAYER[$score->player_id];
        }
        return view('game', [
            'last_round' => $latestRound,
            'rounds' => $previousRounds,
            'players' => $scores
        ]);
    }

    public function round(Request $request, GameService $service)
    {
        $session_id = $request->session()->get('session_id');
        $prompt = trim((string) $request->input('clue', ''));
        $security = GameService::securityGuard($prompt);
        if($security['is_valid']) {
            GameService::playRound($session_id, $prompt);
            return ['status' => 'ok'];
        }
        else {
            return ['status' => 'error'];
        }
    }

    public function reset(Request $request)
    {
        $request->session()->forget('session_id');
        return redirect('/game');
    }
}
