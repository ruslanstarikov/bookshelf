<?php

namespace App\Services;

use Prism\Prism\Enums\Provider;
use Prism\Prism\Facades\Tool;
use Prism\Prism\Prism;

class GameService
{
    public function run(string $prompt, Provider $provider, string $model, string $session_id)
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

        $systemPrompt = "You are an agent that extracts a book's title and ISBN from the user's query.
                         Only use the tool `bookTool` once you infer both title and ISBN, even if you have to guess.
                         If the query is not about a book, immediately call `errorTool`.
                         Do not reply or explain anything to the user. Just invoke the appropriate tool.
                         If you are unsure about the book or have multiple books in mind, pick the first that comes to mind.
                         Do not respond with text, always with tool call.";
        Prism::text()
            ->using($provider, $model)
            ->withSystemPrompt($systemPrompt)
            ->withTools([$isbnTool, $errorTool])
            ->withPrompt($prompt)
            ->asText();
    }

    function validateIsbn(string $isbn, string $title)
    {
        $url = "https://www.googleapis.com/books/v1/volumes?q=isbn:" . urlencode($isbn);

        $response = file_get_contents($url);

        if ($response === false) {
            return null;
        }

        $data = json_decode($response, true);

        if (!isset($data['items'][0]['volumeInfo']['title'])) {
            return null;
        }

        return $data['items'][0]['volumeInfo'];
    }

    public function reportError(string $error)
    {

    }
}
