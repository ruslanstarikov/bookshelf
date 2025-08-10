<?php

namespace App\Console\Commands;

use App\Services\GameService;
use Illuminate\Console\Command;
use Prism\Prism\Enums\Provider;

class TestGameCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-game-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Game Command';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $prompt = $this->ask('Prompt');
        $security = GameService::securityGuard($prompt);
        $session_id = 'XXX000XXX';
        if($security['is_valid']) {
            $result = GameService::playRound($session_id, $prompt);
            $this->info(json_encode($result));
        }
        else
        {
            $this->error($security['error']);
        }
    }
}
