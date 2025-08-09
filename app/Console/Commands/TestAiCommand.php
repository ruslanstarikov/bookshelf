<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Facades\Tool;
use Prism\Prism\Prism;

class TestAiCommand extends Command
{
    protected $signature = 'app:test-ai-command';

    protected $description = 'Test AI stuff';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isbnTool = Tool::as('bookTool')
            ->for('Report your findings on book and isbn. Must provide only isbn and title')
            ->withStringParameter('isbn', 'ISBN of the book')
            ->withStringParameter('title', 'Title of the book')
            ->using([$this, 'validateIsbn']);

        $errorTool = Tool::as('errorTool')
            ->for('Report an error')
            ->withStringParameter('error', 'error message')
            ->using([$this, 'reportError']);

        $prompt = $this->ask("prompt");

        $systemPrompt = "You are an agent that extracts a book's title and ISBN from the user's query.
                         Only use the tool `bookTool` once you infer both title and ISBN, even if you have to guess.
                         If the query is not about a book, immediately call `errorTool`.
                         Do not reply or explain anything to the user. Just invoke the appropriate tool.
                         If you are unsure about the book or have multiple books in mind, pick the first that comes to mind.
                         Do not respond with text, always with tool call.";
        $response = Prism::text()
            ->using(Provider::Mistral, 'mistral-medium-latest')
            ->withSystemPrompt($systemPrompt)
            ->withTools([$isbnTool, $errorTool])
            ->withPrompt($prompt)
            ->asText();

        $this->warn($response->text);
    }

    function validateIsbn(string $isbn, string $title)
    {
        $this->info("ISBN: $isbn TITLE: $title");
    }

    public function reportError(string $error)
    {
        $this->error($error);
    }
}
