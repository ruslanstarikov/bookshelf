<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class RoundController extends Controller
{
    public function play(Request $request)
    {
        $clue = (string) $request->input('clue', '');

        // 1) Get AI guesses (title + isbn) — your local agents (no browsing!)
        // Replace these with real calls to your Prism tools / agents.
        $guesses = $this->askAgentsForGuesses($clue);

        // 2) Validate & score
        $results = [];
        foreach ($guesses as $g) {
            [$exists, $canonicalTitle] = $this->lookupGoogleBooksByIsbn($g['isbn']);
            $score = 0;
            if ($exists) {
                $score = $this->titlesMatch($g['title'], $canonicalTitle) ? 2 : 1;
            }
            $results[] = [
                'guess' => [
                    'name'  => $g['name'],
                    'title' => $g['title'],
                    'isbn'  => $g['isbn'],
                ],
                'score' => $score,
                'canonicalTitle' => $exists ? $canonicalTitle : null,
            ];
        }

        return response()->json(['results' => $results]);
    }

    /** --- Replace with calls to your three agents (Prism tools etc.) --- */
    private function askAgentsForGuesses(string $clue): array
    {
        // Dummy data for now
        return [
            ['name' => 'HAL-9000', 'title' => 'Great Expectations', 'isbn' => '9780141439563'],
            ['name' => 'RoboLit',  'title' => 'War and Peace',      'isbn' => '9780199232765'],
            ['name' => 'BookTron', 'title' => 'Moby-Dick',          'isbn' => '9781503280786'],
        ];
    }

    /** Google Books validation by ISBN (no key needed for basic use) */
    private function lookupGoogleBooksByIsbn(string $isbn): array
    {
        if ($isbn === '') return [false, null];

        $resp = Http::timeout(8)->get('https://www.googleapis.com/books/v1/volumes', [
            'q' => 'isbn:' . $isbn,
        ]);

        if (!$resp->ok()) return [false, null];

        $data = $resp->json();
        $item = $data['items'][0]['volumeInfo'] ?? null;

        if (!$item) return [false, null];

        $title = $item['title'] ?? null;
        return [$title !== null, $title];
    }

    /** Lenient title match: case-insensitive, trims punctuation/colons/whitespace */
    private function titlesMatch(?string $a, ?string $b): bool
    {
        if (!$a || !$b) return false;

        $norm = function (string $s) {
            $s = Str::of($s)
                ->lower()
                ->replace(['—', '–'], '-')  // normalize dashes
                ->replaceMatches('/[^a-z0-9:\- ]+/u', '') // strip symbols
                ->replaceMatches('/\\s+/', ' ')           // collapse spaces
                ->trim();

            // Ignore anything after a colon to avoid subtitle mismatches, try both ways
            $beforeColon = fn($x) => explode(':', $x, 2)[0];

            return [
                (string) $s,
                (string) $beforeColon($s),
            ];
        };

        [$a1, $a2] = $norm($a);
        [$b1, $b2] = $norm($b);

        return ($a1 === $b1) || ($a1 === $b2) || ($a2 === $b1) || ($a2 === $b2);
    }
}
